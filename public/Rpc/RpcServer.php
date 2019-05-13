<?php
ini_set('display_error', 'on');
include_once __DIR__ . '/../RsaHelper.php';
/**
 * Rpc服务端
 */
class RpcServer
{
	protected $server = null;
	protected $msg = '';
	public function __construct($host,$port,$path)
	{
		$this->tcpServer($host,$port,$path);
		// $this->udpServer($host,$port,$path);
	}
	// protected function udpServer()
	// {
	// 	$this->server = stream_socket_server("udp://127.0.0.1:1113",$errno,$errstr,STREAM_SERVER_BIND);
	// 	if (!$this->server) {
	// 		$this->msg = '创建失败:' . $errno."($errstr)";
	// 		return false;
	// 	}

	// 	do {
	// 		$pkt = stream_socket_recvfrom($this->server, 1,0,$peer);
	// 		echo $peer.PHP_EOL;
	// 		$res = date('Y-m-d H:i:s')."\r\n";
	// 		stream_socket_sendto($this->server, $res,0,$peer);
	// 	} while ($pkt !== false);
	// }

	protected function tcpServer($host,$port,$path)
	{
		$this->server = stream_socket_server("tcp://{$host}:{$port}",$errno);
		if (!$this->server) {
			$this->msg = '创建失败:'.$errno;
			return false;
		}
		$realpath = realpath($path);
		if (!$realpath || !file_exists($path)) {
			$this->msg = '目录不存在:'.$realpath.'-'.$path;
			return false;
		}
		$rsa = new RsaHelper();
		$public_key = $rsa->getKey(__DIR__ . '/../../app_public_key.pem');
		$this->msg = '开始进入循环';
		while (true) {
			$client = stream_socket_accept($this->server);
			if ($client) {
				$buf = fread($client,2048);
				$buf = $rsa->publicDecrypt($public_key,$buf);
				if (!$buf) {
					echo "解密失败".PHP_EOL;
					echo $rsa->getMsg().PHP_EOL;
					fclose($client);
					continue;
				}
				$classRet = preg_match('/Rpc-Class:\s(.*);\r\n/i', $buf, $class);
				$methodRet = preg_match('/Rpc-Method:\s(.*);\r\n/i', $buf, $method);
				$paramsRet = preg_match('/Rpc-Params:\s(.*);\r\n/i', $buf, $params);
				if ($classRet && $methodRet) {
					$class = ucfirst($class[1]);
				}
				$method = $method[1];
				$file = $realpath .'/'.$class . '.php';
				if (!file_exists($file)) {
					fwrite($client, "err:file not exists ".$file);
					fclose($client);
					continue;
				}
				// fwrite($client, var_export([$class,$method[1],$params[1],$file],true));
				// fclose($client);
				// continue;
				try {
					require_once $file;
					$obj = new $class();
					if (!$paramsRet) {
						$data = $obj->$method();
					}else{
						// 被调用的方法只能用数组的方式传入单个参数
						$data = $obj->$method(json_decode($params[1]));
					}
					$data = $rsa->publicEncrypt($public_key,$data);
					fwrite($client,$data);
				} catch (\Exception $e) {
					fwrite($client, 'cmp:'.$class.$method.$params);
					fwrite($client, 'err:'.$e->getMessage());
				}
				fclose($client);			
			}
		}
	}
	public function getMsg()
	{
		return $this->msg;
	}
	public function __destruct()
	{
		if ($this->server) {
			fclose($this->server);
		}
	}
	
}
// echo realPath('../');
// echo PHP_EOL;
// echo realpath('../');
// echo PHP_EOL;
// exit;
try {
	$server = new RpcServer("127.0.0.1","8888",'./');
	echo $server->getMsg();
} catch (\Exception $e) {
	echo $e->getMessage();
}


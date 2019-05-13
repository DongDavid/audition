<?php
include_once __DIR__ . '/../RsaHelper.php';
/**
 * Rpc客户端
 */
class RpcClient
{
	
	protected $urlInfo = [];
	protected $msg = '';
	function __construct($url)
	{
		// $url = 'http://username:password@hostname/path?arg=value#anchor';
		// scheme - 如 http
		// host
		// port
		// user
		// pass
		// path
		// query - 在问号 ? 之后
		// fragment - 在散列符号 # 之后
		$this->urlInfo = parse_url($url);
		if (!$this->urlInfo) {
			$this->msg = 'url解析错误';
		}
	}
	public function getMsg()
	{
		return $this->msg;
	}
	// public function postDataFsockopen($url,$data){

 //        $postdata = '';
 //        foreach ($data as $key=>$value){
 //            $postdata.= ($key.'='.urlencode($value).'&');
 //        }
 //        // building POST-request: 
 //        $URL_Info=parse_url($url);
 //        if(!isset($URL_Info["port"])){  
 //            $URL_Info["port"]=80;  
 //        }  
 //        $request = ''; 
 //        $request.="POST ".$URL_Info["path"]." HTTP/1.1\r\n";  
 //        $request.="Host:".$URL_Info["host"]."\r\n";   
 //        $request.="Content-type: application/x-www-form-urlencoded;charset=utf-8\r\n";  
 //        $request.="Content-length: ".strlen($postdata)."\r\n";  
 //        $request.="Connection: close\r\n"; 
 //        $request.="\r\n";  
 //        $request.=$postdata."\r\n";  
 //        $fp = fsockopen($URL_Info["host"],$URL_Info["port"]);  
 //        fputs($fp, $request); 
 //        $result = '';
 //        while(!feof($fp)) { 
 //            $result .= fgets($fp, 128);   
 //        }  
 //        fclose($fp);

 //        $str_s = strpos($result,'{');   
 //        $str_e = strrpos($result,'}'); 
 //        $str = substr($result, $str_s,$str_e-$str_s+1);
 //        //print_r($result);
 //        return $str;
 //    }

	public function __call($method,$params)
	{
		$class = basename($this->urlInfo['path']);
		$proto = "Rpc-Class: {$class};"."\r\n";
		$proto .= "Rpc-Method: {$method};"."\r\n";
		$params = json_encode($params);
		$proto .= "Rpc-Params: {$params};"."\r\n";
		$rsa = new RsaHelper();
		$private_key = $rsa->getKey(__DIR__ . '/../../app_private_key.pem');
		$proto = $rsa->privateEncrypt($private_key,$proto);
		// return $proto;
		$client = stream_socket_client("tcp://{$this->urlInfo['host']}:{$this->urlInfo['port']}",$errno,$errstr);
		if (!$client) {
			$this->msg = $errno.':'.$errstr;
			return false;
		}
		// var_dump($proto);
		fwrite($client, $proto);
		$data = fread($client,2048);
		fclose($client);
		echo $data . PHP_EOL;
		$data = $rsa->privateDecrypt($private_key,$data);
		return $data;
	}
}


<?php
/**
 *     Rsa工具类
 */
class RsaHelper
{
	protected $msg;  
    public function getMsg()
    {
    	return $this->msg;
    }
    public function getKey($key_path)
    {
    	if (!file_exists($key_path)) {
    		$this->msg = '证书文件不存在';
    		return false;
    	}
    	return file_get_contents(realpath($key_path));
    }
    /**
     * RSA私钥加密
     * @param string $private_key 私钥
     * @param string $data 要加密的字符串
     * @return string $encrypted 返回加密后的字符串
     * @author mosishu
     */
    public function privateEncrypt($private_key, $data)
    {
        $encrypted = '';
        $pi_key    = openssl_pkey_get_private($private_key); //这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        if (!$pi_key) {
        	$this->msg = '私钥加载失败';
        	return false;
        }
        //最大允许加密长度为117，得分段加密 128-11 = 117
        $plainData = str_split($data, 100); //生成密钥位数 1024 bit key
        foreach ($plainData as $chunk) {
            $partialEncrypted = '';
            $encryptionOk     = openssl_private_encrypt($chunk, $partialEncrypted, $pi_key); //私钥加密
            if ($encryptionOk === false) {
            	$this->msg = '加密失败';
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        $encrypted = base64_encode($encrypted); //加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $encrypted;
    }

	/**
	 * RSA公钥解密(私钥加密的内容通过公钥可以解密出来)
	 * @param string $public_key 公钥
	 * @param string $data 私钥加密后的字符串
	 * @return string $decrypted 返回解密后的字符串
	 * @author mosishu
	 */
    public function publicDecrypt($public_key, $data)
    {
        $decrypted = '';
        $pu_key    = openssl_pkey_get_public($public_key); //这个函数可用来判断公钥是否是可用的
        if (!$pu_key) {
        	$this->msg = '公钥加载失败';
        	return false;
        }
        $plainData = str_split(base64_decode($data), 128); //生成密钥位数 1024 bit key
        foreach ($plainData as $chunk) {
            $str          = '';
            $decryptionOk = openssl_public_decrypt($chunk, $str, $pu_key); //公钥解密
            if ($decryptionOk === false) {
            	$this->msg = '解密失败';
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

	//RSA公钥加密
    public function publicEncrypt($public_key, $data)
    {
        $encrypted = '';
        $pu_key    = openssl_pkey_get_public($public_key);
        if (!$pu_key) {
        	$this->msg = '公钥加载失败';
        	return false;
        }
        $plainData = str_split($data, 100);
        foreach ($plainData as $chunk) {
            $partialEncrypted = '';
            $encryptionOk     = openssl_public_encrypt($chunk, $partialEncrypted, $pu_key); //公钥加密
            if ($encryptionOk === false) {
            	$this->msg = '加密失败';
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

	//RSA私钥解密
    public function privateDecrypt($private_key, $data)
    {
        $decrypted = '';
        $pi_key    = openssl_pkey_get_private($private_key);
        if (!$pi_key) {
        	$this->msg = '私钥加载失败';
        	return false;
        }
        $plainData = str_split(base64_decode($data), 128);
        foreach ($plainData as $chunk) {
            $str          = '';
            $decryptionOk = openssl_private_decrypt($chunk, $str, $pi_key); //私钥解密
            if ($decryptionOk === false) {
            	$this->msg = '解密失败';
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

}

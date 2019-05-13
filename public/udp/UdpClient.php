<?php

class UdpClient
{
    protected $url = "udp://127.0.0.1:9998";
    protected $client = null;
    protected $msg = '';
    public function __construct()
    {

    }
    public function start($msg) {
        $this->client = stream_socket_client($this->url,$errno,$errstr);
        if (!$this->client) {
            $this->msg = "连接失败" . $errno . "($errstr)";
            return false;
        }
        fwrite($this->client,$msg."\n");
        $data = fread($this->client,1024);
        fclose($this->client);
        return $data;
    }
    public function getMsg() {
        return $this->msg;
    }
}
$client = new UdpClient();
$res = $client->start("你好吗");

var_dump($res);
<?php

class UdpServer
{
    protected $url = "udp://127.0.0.1:9998";
    protected $server = null;
    public function __construct()
    {
        $this->start();
    }
    protected function start() {
        $this->server = stream_socket_server($this->url,$errno,$errstr,STREAM_SERVER_BIND);
        if (!$this->server) {
            $this->msg = "创建链接失败:" . $errno."($errstr)";
            return false;
        }
        do {
            $inMsg = stream_socket_recvfrom($this->server, 1024, 0, $peer);
            echo "Client : $peer \n";
            echo "Receive: $inMsg \n";
            $outMsg = substr($inMsg, 0, (strrpos($inMsg,"\n"))).' -- '.date("D M j H:i:s Y\r\n");
            stream_socket_sendto($this->server, $outMsg, 0, $peer);
        } while ($inMsg !== false);
    }
}
$udp = new UdpServer();
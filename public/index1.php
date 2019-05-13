<?php


include_once 'Rpc/RpcClient.php';

$url = "http://127.0.0.1:8888/rsaHelper";
$url = "http://127.0.0.1:8888/test";
// $url = "http://127.0.0.1:1113";
$client = new RpcClient($url);
if ($client->getMsg()!='') {
	echo $client->getMsg();
	exit;
}

$r = $client->sayWhat('666','645');
var_dump($r);
// $private_key = file_get_contents('../app_private_key.pem');
// $public_key = file_get_contents('../app_public_key.pem');
// $data = ['adwd','vsjoiad'];
// $data = 'adwe';
// $data = $client->publicEncrypt($private_key,$data);

// var_dump($data);

// $originData = $client->privateDecrypt($public_key,$data);

// var_dump($originData);
<?php

include_once 'RsaHelper.php';

$rsa = new RsaHelper();


$public_key 	= __DIR__ . '/../app_public_key.pem';
$private_key 	= __DIR__ . '/../app_private_key.pem';

$public_key = $rsa->getKey($public_key);
$private_key = $rsa->getKey($private_key);




$data = "你是猪吗， 你才是猪呢！！！";


$public_data = $rsa->publicEncrypt($public_key,$data);

var_dump($public_data);

$private_data = $rsa->privateEncrypt($private_key,$data);

var_dump($private_data);

$decryptPrivateData = $rsa->privateDecrypt($private_key,$public_data);

var_dump($decryptPrivateData);

$decryptPublicData = $rsa->publicDecrypt($public_key,$private_data);


var_dump($decryptPublicData);
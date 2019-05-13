# Rsa加密


创建公钥和私钥

```shell
# 进入openssl命令行
$ openssl
# 生成私钥
OpenSSL> genrsa -out app_private_key.pem 1024
Generating RSA private key, 1024 bit long modulus
.....++++++
.......++++++
e is 65537 (0x10001)
# 根据私钥生成公钥
OpenSSL> rsa -in app_private_key.pem -pubout -out app_public_key.pem
writing RSA key
OpenSSL> exit
```

```shell
# 私钥转成 PKCS8 格式
OpenSSL> pkcs8 -topk8 -inform PEM -in app_private_key.pem -outform PEM -nocrypt -out app_private_key_pkcs8.pem 
```

## 说明

私钥的加密内容可以被公钥解开

公钥的加密内容可以被私钥解开

私钥的加密内容不能被私钥解开

公钥的加密内容不能被公钥解开

`openssl_pkey_get_public`和`openssl_pkey_get_private`方法接收的是证书的内容而不是路径

## 其他

同一段内容，公钥加密出来的字符串是不一样的，而私钥解密出来的字符串是固定不变的

这个问题和PKCS #1 v1.5指定的padding的填充方式有关

- PKCS Public 公钥加密标准(Key Cryptography Standards)

公钥是02，每次填充的都是随机字符串， 而私钥是固定为00 或01

默认是`OPENSSL_PKCS1_PADDING`,

如果选择了不填充`OPENSSL_NO_PADDING`，则需要手动填充
可以使用`str_pad`函数进行填充
```php
# 默认用空格填充右侧
$data = str_pad($data,128)
# 设置为0 填充
$data = str_pad($data,128,'0')
# str_pad(string,length,pad_string,pad_type)
# pad_type 规定填充方向 选择BOTH的时候如果不是偶数会额外填充右侧
# STR_PAD_BOTH; //2
# STR_PAD_RIGHT; //1
# STR_PAD_LEFT; //0
```


## Rpc远程调用

`ucfirst`
将字符串的首字母转换为大写



## TCP和UDP的区别

* TCP 连接前需要进行三次握手确认身份，而后开始发送数据，且传输数据时有确认重传等机制，数据传输完成后会断开连接，整体资源消耗高于UDP

* UDP 连接时不需要确认身份，直接发送数据，速度快， 性能高，但是不稳定，网络不好时容易丢包

* UDP无法保证数据包的顺序，而TCP可以保证顺序

* UDP 没有拥赛控制，不会因为网络阻赛降低主机的传送速率，对实时应用很有用

* TCP是一对一的，UDP支持一对一、一对多、多对一、多对多的交互通信

* TCP是全双工 UDP因为连连接都没有，所以就没有所谓的全双工半双工的说法

## php

匿名函数中使用use传入的参数若不使用&则不会再更改了， 会在匿名函数创建时就固定下来。
```php
$a = function($name,$class) use ($age) {
	// to do something
}
```


## redis

- string 字符串、整数、浮点
- list 一个序列集合
- set 各不相同的元素
- hash k-v散列组
- sort set 带分数的s-v有序集合 其中score为浮点 value 为元素

##  Linux 命令

```shell
ps
-au 显示较详细的资讯
-aux 显示所有包含其他使用者的行程
-ef //显示所有命令，连带命令行
-u root //显示root进程用户信息
```

## PSR

[中文版PSR](http://psr.phphub.org/)  

[GitHub上的原装货](https://github.com/php-fig/fig-standards/tree/master/accepted)  


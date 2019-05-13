<?php

$redis = new \Redis();

$redis->connect('172.19.0.2','6379');
/*************** string ******************/
// 删除
$redis->delete("string1");

// 设置
$redis->set("string1","val1");
// 查找
$val = $redis->get("string1");

echo "string1的值：";
var_dump($val);
echo "<br>";
$redis->set("string1",4);
$redis->incr("string1");
echo "string1的值：";
$val2 = $redis->get("string1");
var_dump($val2);
echo "<br>";
echo "string1的值：";
$redis->decr("string1",2);
$val2 = $redis->get("string1");
var_dump($val2);

echo "<br>";

/*************** list ******************/

$redis->delete("list1");

$redis->lPush("list1","A");
$redis->lPush("list1","C");
$redis->lPush("list1","B");
$val = $redis->rPop("list1");
var_dump($val);
echo "<br>";

$val1 = $redis->lPop("list1");
var_dump($val1);
echo "<br>";


/*************** set ******************/
//元素唯一
$redis->delete("set1");

$redis->sAdd("set1","A");
$redis->sAdd("set1","B");
$redis->sAdd("set1","C");
$redis->sAdd("set1","C");
// 查看元素个数
var_dump($redis->sCard("set1"));

$val = $redis->sMembers("set1");
var_dump($val);

for ($i = 0; $i< 4; $i++) {
    var_dump($redis->sPop("set1"));
}
echo "<br>";
/*************** hash ******************/

$redis->delete("hash1");

$redis->hSet("hash1","name","ddv");
$redis->hSet("hash1","age",24);
$redis->hSet("hash1","job","666");

$val = $redis->hGet("hash1","name");
var_dump($val);
echo "<br>";
$all = $redis->hGetAll("hash1");
var_dump($all);
echo "<br>";

/*************** sort set ******************/

$redis->delete("sortset1");

$redis->zAdd("sortset1",83,"ppx");
$redis->zAdd("sortset1",66,"ddv");
$redis->zAdd("sortset1",49,"qqq");
//从低到高输出
$val = $redis->zRange("sortset1",0,-1);
var_dump($val);
echo "<br>";
$val1 = $redis->zRevRange("sortset1",0,-1);
var_dump($val1);
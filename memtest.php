<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/8/7
 * Time: 9:09
 */
header("Content-Type:text/html;charset=utf-8");
$memcacheConnect = new Memcache();
if($memcacheConnect->connect('localhost',11211)){
    echo "连接成功";
}else{
    echo "连接失败";
}
echo "<br/>";


$memcacheConnect->add("test",123);//加入flag和过期时间后会出错，暂时不知道原因
$cache = $memcacheConnect->get("test");
//var_dump($cache);
$array = ['name'=>"yhwang",'age'=>22,'from'=>"jt"];


//序列化
$sa = serialize($array);
$memcacheConnect->add("sa",$sa);
$ssa = $memcacheConnect->get("sa");
echo "序列化";echo "<br/>";
var_dump($ssa);echo "<br/>";
$usa = unserialize($ssa);
var_dump($usa);


//json会把一个数组变成一个对象。。。。。。。。。这真奇怪
echo "json";echo "<br/>";
$jsa = json_encode($array);
$memcacheConnect->add("jsa",$jsa);
$jsa = $memcacheConnect->get("jsa");
var_dump($jsa);echo "<br/>";
$ujsa = json_decode($jsa);
var_dump($ujsa);echo "<br/>";
https://github.com/letmeseesee/DesignPatternsPHP.git

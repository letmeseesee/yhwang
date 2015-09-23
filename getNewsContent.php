<?php
/**
 * Created by PhpStorm.
 * User: yhwang
 * Date: 2015/9/23
 * Time: 16:20
 * 创建用来获取最新的信息
 */
$userId = $_POST['userId'];
require_once("./db_fns.php");
$db = new db();
$sql = "select * from meaasge";
$db->query($sql);
if($db->result['result']){
    echo json_encode($db->result['data']);
}else{

}

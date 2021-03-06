<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:55
 * 验证用户
 */
include_once("include_fns.php");
$db = new db();//实例化一个数据库连接实例
function login($user,$pass){//用户登录
    global $db;
    $sql = "select * from uesrs WHERE username = ".$user ." and password = ".$pass;
    $db->query($sql);
    if(!$db->result['result']){
        return false;
    }
    return true;
}
function number_of_accounts($username){//判断当前用户的账户数
    global $db;
    $sql = "select * from accounts WHERE username = ".$username;
    $db->query($sql);
    if($db->result['result']){
        return $db->result['number'];
    }
    return false;
}
function get_account_list($username){//返回上次查询的结果
    global $db;
    global $db;
    $sql = "select * from accounts WHERE username = ".$username;
    $db->query($sql);
    if($db->result['result']){
        return $db->result['data'];
    }
    return false;
}
function delete_account($username,$account){//删除用户,并且删除该用户所有的邮件账户,$account是当前账户的id
    global $db;
    $sql = "delete from uesrs WHERE accountid = ".$account ." and username = ".$username;
    $db->exec($sql);//不判断是否
    return true;
}
function store_account_settings($username,$settings){//先不判断sql操作是否成功
    global $db;
    if(!filled_out($settings)){
        echo "<p>All field must be filled!</p>";
    }else{
        if($settings['account']){//更新
            $sql= "update accounts set sever = ".$settings['sever'].
                " port = ".$settings['port']." type = ".$settings['type']." remoteuser = ".$settings['remoteuser'].
            " remotepassword = ".$settings['remotepassword'];
            $db->exec($sql);
        }else{//插入
            $sql = "Insert into account VALUE (".$username.",".$settings['sever'].",".$settings['port'].","
                .$settings['type']."," .$settings['remoteuser'].",".$settings['remotepassword'].","
                ."Null)";
            $db->exec($sql);
        }
    }
    return true;
}
function filled_out($settings){//判断表单是否完整
    return true;
}
function account_exists($username,$account){
    global $db;
    $sql = "select * from accounts WHERE username = ".$username." and accountid = ".$account;
    $db->query($sql);
    return $db->result['result'];
}
function check_auth_user(){
    return true;
}
function formet_action($action){
    return true;
}
function send_message($to,$cc,$subject,$message){
    return true;
}
function delete_message($user,$account,$messageId){
    return true;
}
function open_mailbox($user,$account){
//    if(number_of_accounts($user) == 1){
//        $accounts = get_account_list($user);
//        $_SESSION['selected_account'] = $accounts[0];
//        $account = $accounts[0];
//    }
    $settings = get_account_setting($user,$account);
    if(!sizeof($settings)){
        return 0;
    }
    $mailbox = "{".$settings['sever'];
    if($settings['type'] == 'POP3'){
        $mailbox .= "/POP3";
    }
    $mailbox .= ":".$settings['port']."}INBOX";
    $imap = @imap_open($mailbox,$settings['remoteuser'],$settings['remotepassword']);
    return $imap;
}
function get_account_setting($user,$account){//读取账户的设定
    return true;
}
function add_quoting($body){
    return true;
}
function get_accounts($user){
    global $db;
    $list = array();
    $sql = "select * from accounts where username = ".$user;
    $db->query($sql);
    $list = $db->result['data'];
    return $list;
}

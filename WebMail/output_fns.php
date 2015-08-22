<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:55
 * 输出页面
 */
include_once("include_fns.php");
function display_new_message_from($user,$to,$cc,$subject,$body){
    return true;
}
function display_toolbar($buttons){
    return true;
}
function display_login_form($action){
    return true;
}
function do_html_footer(){
    return true;
}
function display_list($user,$account){
    return true;
}
function display_message($user,$account,$messageId,$fullheaders){
    return true;
}
function display_account_setup($user){
    //显示空白的的新用户表单
    display_account_form($user);
    $list = get_accounts($user);
    $accounts = sizeof($list);
    foreach($list as $key=>$account){
        display_account_form($user,$account['accountid'],$account['sever'],$account['remoteuser'],
            $account['remotepassword'],$account['type'],$account['port']);
    }
}
function display_account_form($user){//显示邮件账户

}
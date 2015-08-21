<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:55
 * 验证用户
 */
function login($user,$pass){
    return true;
}
function number_of_accounts($username){
    return true;
}
function get_account_list($username){
    return true;
}
function delete_account($username,$account){//删除用户
    return true;
}
function store_account_settings($username,$account){
    return true;
}
function account_exists($username,$account){
    return true;
}
function check_auth_user(){
    return true;
}
function do_html_header($user,$action,$selectAccount){
    return true;
}
function formet_action($action){
    return true;
}
function display_account_setup($user){
    return true;
}
function send_message($to,$cc,$subject,$message){
    return true;
}
function delete_message($user,$account,$messageId){
    return true;
}
function display_list($user,$account){
    return true;
}
function display_message($user,$account,$messageId,$fullheaders){
    return true;
}
function open_mailbox($user,$account){
    return true;
}
function add_quoting($body){
    return true;
}
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
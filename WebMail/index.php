<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:51
 * 整个系统的主要运行文件
 * 1.先解决登陆问题
 */
include_once("include_fns.php");
//if(!$db->ifConnectSuccess()){
//    echo "数据库连接失败";
//    exit;
//}
session_start();
//登陆信息
$username = $_POST['username'];
$password = $_POST['password'];
$action = $_POST['action'];
$account = $_POST['account'];
$messageId = $_POST['messageId'];
//邮件信息
$to = $_POST['to'];
$cc = $_POST['cc'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$buttons = array();
$status = '';
if($username || $password){
    if(login($username,$password)){
        $status .= "<p style='padding-bottom: 100px'> Logged in success.</p>";
        $_SESSION['auth_user'] = $username;
        if(number_of_accounts($_SESSION['auth_user']) == 1){
            $accounts = get_account_list($_SESSION['auth_user']);
            $_SESSION['select_account'] = $accounts[0];
        }
    }else{
        $status .= "<p style='padding-bottom: 100px'>Sorry,login in failed.</p>";
    }
}
if($action == 'log-out'){
    session_destroy();
    unset($action);
    $_SESSION = array();
}

switch($action){
    case 'delete-account':delete_account($_SESSION['auth_user'],$account);break;//确认删除
    case 'store-setting' :store_account_settings($_SESSION['auth_user'],$_POST);break;//保存设置,用户和当前的邮件账户
    case 'select-account':if(($account) && (account_exists($_SESSION['auth_user'],$account))){
        $_SESSION['select_account'] = $account;
    };break;
}
$buttons[0] = 'view-mailbox';
$buttons[1] = 'new-message';
$buttons[2] = 'account-setup';
if(check_auth_user()){
    $buttons[4] = 'log-out';
}

/*
 * Stage 2:headers
 * Send the HTML headers and menu bar appropriate to current action
 * 就是根据action来显示头部
 * **/
if($action){
    do_html_header($_SESSION['auth_user'],"Warm Mail - ".formet_action($action),$_SESSION['selected_account']);
}else{
    do_html_header($_SESSION['auth_user'],"Warm Mail",$_SESSION['selected_account']);
}
display_toolbar($buttons);

/*
 * Stage 3:根据当前的action来显示body(正文)
 * **/
if(!check_auth_user()){
    echo "<p>You need to log in</p>";
    display_login_form($action);
}else{
    switch($action){
        case 'store-settings':
        case 'account-setup' :
        case 'delete-account': display_account_setup($_SESSION['auth_user']);break;//显示同一个正文
        case 'send-message'  :if(send_message($to,$cc,$subject,$message)){
                                        echo "<p style='padding-bottom: 100px'>Message sent.</p>";
                                }else{
                                        echo "<p style='padding-bottom: 100px'>Message sent failed.</p>";
                                }//发送邮件的正文
            break;
        case 'delete':
            delete_message($_SESSION['auth_user'],$_SESSION['select_account'],$messageId);break;
        case 'select-account':
        case 'view-mailbox':display_list($_SESSION['auth_user'],$_SESSION['select_account']);break;
        case 'show-headers':
        case 'hide-headers':
        case 'view-message':
            $fullheaders = ($action == 'show-headers');
            display_message($_SESSION['auth_user'],$_SESSION['select_account'],$messageId,$fullheaders);
            break;
        case 'reply-all'://回复方式不同,会不会预先填满一些信息
            if(!$imap){
                $imap = open_mailbox($_SESSION['auth_user'],$_SESSION['select_account']);
            }
            if($imap){
                $header = imap_header($imap,$messageId);
                if($header->reply_toaddress){
                        $to = $header->reply_toaddress;
                }else{
                    $to = $header->fromaddress;
                }
                $cc = $header->ccaddress;
                $subject = "Re: ".$header->subject;
                $body = add_quoting(stripcslashes(imap_body($imap,$messageId)));
                imap_close($imap);
                display_new_message_from($_SESSION['auth_user'],$to,$cc,$subject,$body);
            }
            break;
        case 'reply':
            if(!$imap){
                $imap = open_mailbox($_SESSION['auth_user'],$_SESSION['select_account']);
            }
            if($imap){
                $header = imap_header($imap,$messageId);
                if($header->reply_toaddress){
                    $to = $header->reply_toaddress;
                }else{
                    $to = $header->fromaddress;
                }
                $subject = "Re: ".$header->subject;
                $body = add_quoting(stripcslashes(imap_body($imap,$messageId)));
                imap_close($imap);
                display_new_message_from($_SESSION['auth_user'],$to,$cc,$subject,$body);
            }
            break;
        case 'forward':
            if(!$imap){
                $imap = open_mailbox($_SESSION['auth_user'],$_SESSION['select_account']);
            }
            if($imap){
                $header = imap_header($imap,$messageId);
                $subject = "Fwd: ".$header->subject;
                $body = add_quoting(stripcslashes(imap_body($imap,$messageId)));
                imap_close($imap);
                display_new_message_from($_SESSION['auth_user'],$to,$cc,$subject,$body);
            }
            break;
        case 'new-message'://填写新信息
            display_new_message_from($_SESSION['auth_user'],$to,$cc,$subject,$body);
            break;
    }
}

/*
 * 显示尾部
 * **/
do_html_footer();
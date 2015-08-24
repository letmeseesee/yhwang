<?php
/**
 * Created by PhpStorm.
 * User: wanghao
 * Date: 15/8/20
 * Time: 上午6:55
 * 输出页面
 */
include_once("include_fns.php");

function do_html_header($user,$action,$selectAccount){//展示页面头部头部
    if(number_of_accounts($user)>1){
        echo "<form action='index.php?action=open-mailbox' method='post'>
                <td bgcolor='#ff6600' align='right' valign='middle'>";
        display_account_select($user,$selectAccount);
        echo "</td></form>";
    }
    return true;
}
/*
 * $user:当前的用户
 * $selectAccount:当前用户选择的邮件账户
 * **/
function display_account_select($user,$selectAccount){
    //首先选择当前账户的邮箱
    $accounts = get_accounts($user);
    echo "<select onchange='window.location = this.options[selectedIndex].value'>";
    foreach($accounts as $key=>$value){
        if($value['accountid'] == $accounts){
            echo "<option value='index.php?action=select-account&account=".$value['accountid']." selected>"
                .$value['remoteuser']."</option>";
        }else{
            echo "<option value='index.php?action=select-account&account=".$value['accountid']." >"
                .$value['remoteuser']."</option>";
        }
    }
    echo "</select>";
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
function display_list($user,$account){//显示邮箱内容
    global $db;
    if(!$user || !$account){
        echo "<p style='padding-bottom: 100px'>No mailbox selected</p>";
    }else{
        $imap = open_mailbox($user,$account);//
        if(!$imap){
           echo "<p style='padding-bottom: 100px'>Could not open the mailbox!</p>";
        }else{
            echo "<table width='1000px' cellspacing='0' cellpadding='6' border='0'>";
            $headers = imap_headers($imap);
            $messages = sizeof($headers);
            for($i = 0;$i<$messages;$i++){
                echo "<tr><td bgcolor='#ffffff'><a href='index.php?action=view-message&message='"
                    .($i+1).">".$headers[$i]."</a></td></tr>";
            }
            echo "</table>";
        }
    }
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
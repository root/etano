<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/message_proxy.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
require_once '../includes/tables/queue_message.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='inbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$mail_id=sanitize_and_format_gpc($_POST,'mail_id',TYPE_INT,0,0);
	$num_messages=0;
	if (!empty($mail_id)) {
		$num_messages=1;
	}

	if ($_POST['act']=='del') {
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}
		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `del`=1 WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s messages deleted.',$num_messages);
		$nextpage='inbox.php';
	} elseif ($_POST['act']=='move') {
		$folder_id=sanitize_and_format_gpc($_POST,'folder_id',TYPE_INT,0,0);
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}
		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`='$folder_id' WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s messages moved.',$num_messages);
		$nextpage='inbox.php';
	} elseif ($_POST['act']=='reply') {
		$qs='mail_id='.$mail_id;
		redirect2page('message_send.php',array(),$qs);
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
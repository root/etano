<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/message_proxy.php
$Revision$
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
require_once '../includes/tables/user_spambox.inc.php';

db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$qs='';
$qs_sep='';
$topass=array();
$nextpage='mailbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$mail_id=sanitize_and_format_gpc($_POST,'mail_id',TYPE_INT,0,0);
	$num_messages=0;
	if (!empty($mail_id)) {
		$num_messages=1;
	}

	if ($_POST['act']=='del') {
		$folder_id=sanitize_and_format($_POST['mailbox_id'],TYPE_INT,0,0);
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s message(s) permanently deleted.',$num_messages);     // translate
		
		switch ($folder_id) {
			
			case _FOLDER_TRASH_:    
				$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('$mail_id') AND `del`=1 AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;
			
			case _FOLDER_OUTBOX_:	 // Outbox
				$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;
			
			case _FOLDER_SPAMBOX_:	 // Spambox
				$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;
			
			default:
				$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`=0,`del`=1 WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['text']=sprintf('%1s message(s) deleted.',$num_messages);     // translate
				break;		
		
		}
		
		$nextpage='mailbox.php';		
		$qs.=$qs_sep.'fid='.$folder_id;
		$qs_sep='&';
	} elseif ($_POST['act']=='move') {
		$folder_id=sanitize_and_format_gpc($_POST,'folder_id',TYPE_INT,0,0);
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}
		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`='$folder_id', `del`=0 WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s message(s) moved.',$num_messages);     // translate
		$nextpage='mailbox.php';
		$qs.=$qs_sep.'fid='.$folder_id;
		$qs_sep='&';
	} elseif ($_POST['act']=='spam') {
		$query="SELECT * FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`='$mail_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$mail=mysql_fetch_assoc($res);
			if ($mail['message_type']!=_MESS_SYSTEM_) {
				foreach ($user_spambox_default['types'] as $k=>$v) {
					$mail[$k]=sanitize_and_format_gpc($mail,$k,$__html2type[$v],$__html2format[$v],$user_spambox_default['defaults'][$k]);
				}
				$query="INSERT INTO `{$dbtable_prefix}user_spambox` SET `fk_user_id`='".$_SESSION['user']['user_id']."'";
				foreach ($user_spambox_default['defaults'] as $k=>$v) {
					if (isset($mail[$k]) && $k!='fk_user_id') {
						$query.=",`$k`='".$mail[$k]."'";
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`='$mail_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$nextpage='mailbox.php';
				$qs.=$qs_sep.'fid='.$folder_id;
				$qs_sep='&';
			}
		}
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
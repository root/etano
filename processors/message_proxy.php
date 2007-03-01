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

	if ($_POST['act']=='del') {
		$fid=sanitize_and_format($_POST['fid'],TYPE_INT,0,0);
		$num_messages=1;
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}

		switch ($fid) {

			case _FOLDER_TRASH_:
				$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('$mail_id') AND `del`=1 AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;

			case _FOLDER_OUTBOX_:
				$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;

			case _FOLDER_SPAMBOX_:
				$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;

			default:
				$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`=0,`del`=1 WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				break;

		}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s message(s) deleted.',$num_messages);     // translate
		$qs.=$qs_sep.'fid='.$fid;
		$qs_sep='&';
	} elseif ($_POST['act']=='move') {
		$fid=sanitize_and_format_gpc($_POST,'fid',TYPE_INT,0,0);
		$moveto_fid=sanitize_and_format_gpc($_POST,'moveto_fid',TYPE_INT,0,0);
		$num_messages=0;
		if (!empty($mail_id)) {
			$num_messages=1;
		}
		if (is_array($mail_id)) {
			$num_messages=count($mail_id);
			$mail_id=join("','",array_keys($mail_id));
		}
		if ($fid>0 || $fid==_FOLDER_INBOX_ || $fid==_FOLDER_TRASH_) {
			if ($moveto_fid>0 || $moveto_fid==_FOLDER_INBOX_) {	// user_inbox to user_inbox
				$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`='$moveto_fid', `del`=0 WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		} elseif ($fid==_FOLDER_SPAMBOX_) {
			if ($moveto_fid>0 || $moveto_fid==_FOLDER_INBOX_) {	// user_spambox to user_inbox
				$query="INSERT INTO `{$dbtable_prefix}user_inbox` (`is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`,`fk_folder_id`,`del`) SELECT `is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`,'$moveto_fid',0 FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s message(s) moved',$num_messages);     // translate
		$qs.=$qs_sep.'fid='.$fid;
		$qs_sep='&';
	} elseif ($_POST['act']=='spam') {	// user_inbox to user_spambox
		$query="INSERT INTO `{$dbtable_prefix}user_spambox` (`is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`) SELECT `is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('$mail_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$qs.=$qs_sep.'fid='.$fid;
		$qs_sep='&';
	} elseif ($_POST['act']=='reply') {
		$nextpage='message_send.php';
		$qs.=$qs_sep.'mail_id='.$mail_id;
		$qs_sep='&';
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
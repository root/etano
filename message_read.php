<?php
/******************************************************************************
newdsb
===============================================================================
File:                       message_read.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$content='';
if (isset($_GET['mail_id']) && !empty($_GET['mail_id']) && isset($_GET['fid'])) {
	$mail=$user_inbox_default['defaults'];
	$mail['mail_id']=(int)$_GET['mail_id'];
	$fk_folder_id=(int)$_GET['fid'];
	$mailbox_name='Inbox';
	$mailbox_table='inbox';
	
	$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$folders=array();
	$folders2=array();
	$folders2[_FOLDER_INBOX_]='Inbox';
	while ($rsrow=mysql_fetch_row($res)) {
		$folders[$rsrow[0]]=$rsrow[1];
		$folders2[$rsrow[0]]=$rsrow[1];
	}
	if (!empty($folders2) && $fk_folder_id!=_FOLDER_OUTBOX_ && $fk_folder_id!=_FOLDER_SPAMBOX_) {
		unset($folders2[$fk_folder_id]);
		$tpl->set_var('folder_options',vector2options($folders2));
	}
	
	switch ($fk_folder_id) {

		case _FOLDER_INBOX_:						
			$tpl->set_var('inbox_options',true);
			$tpl->set_var('spambox_options',true);
			break;

		case _FOLDER_TRASH_:
			$tpl->set_var('inbox_options',true);
			$tpl->set_var('spambox_options',true);
			$mailbox_name='Trash';
			break;

		case _FOLDER_OUTBOX_:
			$tpl->set_var('outbox_options',true);
			$mailbox_name='Outbox';
			$mailbox_table='outbox';
			break;

		case _FOLDER_SPAMBOX_:
			$tpl->set_var('inbox_options',true);
			$mailbox_name='Spambox';
			$mailbox_table='spambox';
			break;

		default:
			$tpl->set_var('inbox_options',true);
			$tpl->set_var('spambox_options',true);
			$mailbox_name=$folders[$fk_folder_id];
			break;

	}
		
	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_sent`) as `date_sent`,b.`fk_user_id` as `other_id`,b.`_photo` as `photo` FROM `{$dbtable_prefix}user_".$mailbox_table."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id_other`=b.`fk_user_id` WHERE a.`fk_user_id`='".$_SESSION['user']['user_id']."' AND a.`mail_id`='".$mail['mail_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$mail=mysql_fetch_assoc($res);
		if ($mail['message_type']==_MESS_MESS_) {
			check_login_member(4);
		} elseif ($mail['message_type']==_MESS_FLIRT_) {
			check_login_member(6);
		} elseif ($mail['message_type']==_MESS_SYSTEM_) {
			$mail['_user_other']='SYSTEM';     // translate
			$tpl->set_var('spambox_options',false);
		}
		$mail['date_sent']=strftime($_user_settings['datetime_format'],$mail['date_sent']+$_user_settings['time_offset']);
		if (empty($mail['photo'])) {
			$mail['photo']='no_photo.gif';
		}
		if (empty($mail['other_id'])) {
			unset($mail['other_id']);
		} else {
			$query="SELECT * FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `filter_type`='"._FILTER_USER_."' AND `field_value`='".$mail['other_id']."' AND `fk_folder_id`='"._FOLDER_SPAMBOX_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$tpl->set_var('unblock_user',true);
			}
		}

		$mail=sanitize_and_format($mail,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

		$tpl->set_file('content','message_read.html');
		$tpl->set_var('mail',$mail);
		$tpl->set_var('mailbox_id',$fk_folder_id);
		$tpl->set_var('mailbox_name',$mailbox_name);
		if (isset($_GET['o'])) {
			$tpl->set_var('o',$_GET['o']);
		}
		if (isset($_GET['r'])) {
			$tpl->set_var('r',$_GET['r']);
		}
		if (isset($_GET['ob'])) {
			$tpl->set_var('ob',$_GET['ob']);
		}
		if (isset($_GET['od'])) {
			$tpl->set_var('od',$_GET['od']);
		}
		$tpl->process('content','content',TPL_OPTIONAL);
		if ($fk_folder_id!=_FOLDER_OUTBOX_){
			$query="UPDATE `{$dbtable_prefix}user_".$mailbox_table."` SET `is_read`=1 WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `mail_id`='".$mail['mail_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}

		if (is_file('message_read_left.php')) {
			include 'message_read_left.php';
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No such message.';      // translate
		redirect2page('mailbox.php');
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No such message.';     // translate
	redirect2page('mailbox.php');
}

$tplvars['title']='Read a message';     // translate
include 'frame.php';
?>
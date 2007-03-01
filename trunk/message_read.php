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
// no check_login_member() here. It is used down below

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$content='';
if (isset($_GET['mail_id']) && !empty($_GET['mail_id']) && isset($_GET['fid'])) {
	$mail=$user_inbox_default['defaults'];
	$mail['mail_id']=(int)$_GET['mail_id'];

	$my_folders=array(_FOLDER_INBOX_=>'INBOX',_FOLDER_OUTBOX_=>'OUTBOX',_FOLDER_TRASH_=>'Trash',_FOLDER_SPAMBOX_=>'SPAMBOX'); // translate this
	$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_row($res)) {
		$my_folders[$rsrow[0]]=sanitize_and_format($rsrow[1],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	}

	$fid=_FOLDER_INBOX_;
	if (isset($_GET['fid']) && !empty($_GET['fid']) && isset($my_folders[$_GET['fid']])) {
		$fid=(int)$_GET['fid'];
	}
	$moveto_folders=$my_folders;
	unset($moveto_folders[_FOLDER_SPAMBOX_]);
	unset($moveto_folders[_FOLDER_OUTBOX_]);
	unset($moveto_folders[$fid]);

	$mailbox_table='inbox';
	$where="a.`fk_user_id`='".$_SESSION['user']['user_id']."' AND a.`mail_id`='".$mail['mail_id']."'";
	switch ($fid) {

		case _FOLDER_INBOX_:
			$tpl->set_var('spam_controls',true);
			break;

		case _FOLDER_TRASH_:
			break;

		case _FOLDER_OUTBOX_:
			$mailbox_table='outbox';
			$tpl->set_var('is_outbox',true);
			break;

		case _FOLDER_SPAMBOX_:
			$mailbox_table='spambox';
			break;

		default:
			$tpl->set_var('spam_controls',true);
			break;

	}

	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_sent`) as `date_sent`,b.`fk_user_id` as `other_id`,b.`_photo` as `photo`,c.`last_activity` FROM `{$dbtable_prefix}user_{$mailbox_table}` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id_other`=b.`fk_user_id` LEFT JOIN `{$dbtable_prefix}online` c ON a.`fk_user_id_other`=c.`fk_user_id` WHERE $where LIMIT 1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$mail=mysql_fetch_assoc($res);
		$mail['date_sent']=strftime($_user_settings['datetime_format'],$mail['date_sent']+$_user_settings['time_offset']);
		$mail['subject']=sanitize_and_format($mail['subject'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

		switch ($mail['message_type']) {

			case _MESS_MESS_:
				$mail['message_body']=sanitize_and_format($mail['message_body'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
				check_login_member(4);
				break;

			case _MESS_FLIRT_:
				check_login_member(6);
				break;

			case _MESS_SYSTEM_:
				check_login_member(-1);
				$mail['_user_other']='SYSTEM';     // translate
				$tpl->set_var('spam_controls',false);
				break;

		}
		$mail['message_body']=bbcode2html($mail['message_body']);
		if (empty($mail['photo'])) {
			$mail['photo']='no_photo.gif';
		}
		if (empty($mail['other_id'])) {
			unset($mail['other_id']);
		} else {
			if (!empty($mail['last_activity'])) {
				$mail['is_online']='is_online';
			}
			$query="SELECT `filter_id` FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `filter_type`='"._FILTER_USER_."' AND `field_value`='".$mail['other_id']."' AND `fk_folder_id`='"._FOLDER_SPAMBOX_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$tpl->set_var('is_blocked',true);
			}
		}

		$tpl->set_file('content','message_read.html');
		$tpl->set_var('mail',$mail);
		$tpl->set_var('fid',$fid);
		$tpl->set_var('mailbox_name',$my_folders[$fid]);
		$tpl->set_var('folder_options',vector2options($moveto_folders));
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
		if ($mail['is_read']==0) {
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
$tplvars['page_title']='Read a message';
$tplvars['page']='message_read';
$tplvars['css']='message_read.css';
include 'frame.php';
?>
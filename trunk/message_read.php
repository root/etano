<?php
/******************************************************************************
newdsb
===============================================================================
File:                       message_read.php
$Revision: 85 $
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
if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
	$mail=$user_inbox_default['defaults'];
	$mail['mail_id']=(int)$_GET['mail_id'];

	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_sent`) as `date_sent`,b.`fk_user_id` as `from_id`,b.`_photo` as `photo` FROM `{$dbtable_prefix}user_inbox` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id_from`=b.`fk_user_id` WHERE a.`fk_user_id`='".$_SESSION['user']['user_id']."' AND a.`mail_id`='".$mail['mail_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$mail=mysql_fetch_assoc($res);
		if ($mail['message_type']==_MESS_MESS_) {
			check_login_member(4);
		} elseif ($mail['message_type']==_MESS_FLIRT_) {
			check_login_member(6);
		}
		$mail['date_sent']=strftime($_user_settings['datetime_format'],$mail['date_sent']+$_user_settings['time_offset']);
		if (empty($mail['photo'])) {
			$mail['photo']='no_photo.gif';
		}
		if (empty($mail['from_id'])) {
			unset($mail['from_id']);
		}

		$mail=sanitize_and_format($mail,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

		$tpl->set_file('content','message_read.html');
		$tpl->set_var('mail',$mail);
		$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$folders=array();
		while ($rsrow=mysql_fetch_row($res)) {
			$folders[$rsrow[0]]=$rsrow[1];
		}
		if (!empty($folders)) {
			$tpl->set_var('folder_options',vector2options($folders));
		}
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
		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `is_read`=1 WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `mail_id`='".$mail['mail_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		if (is_file('message_read_left.php')) {
			include 'message_read_left.php';
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No such message.';
		redirect2page('inbox.php');
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No such message.';
	redirect2page('inbox.php');
}

$tplvars['title']='Read a message';
include 'frame.php';
?>
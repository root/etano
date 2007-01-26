<?php
/******************************************************************************
newdsb
===============================================================================
File:                       mailbox.php
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
check_login_member(4);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : 7;
$od=isset($_GET['od']) ? (int)$_GET['od'] : 1;
$orderkeys=array_keys($user_inbox_default['defaults']);
$orderby='';
if ($ob>=0) {
	$orderby='ORDER BY `'.$orderkeys[$ob].'`';
	if ($od==0) {
		$orderby.=' ASC';
	} else {
		$orderby.=' DESC';
	}
}
$mailbox_name='Inbox';     // translate
$fk_folder_id=_FOLDER_INBOX_;
$del=0;
$from="`{$dbtable_prefix}user_inbox`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";
$is_read='`is_read`,';
if (isset($_GET['fid']) && !empty($_GET['fid'])) {
	$fk_folder_id=(int)$_GET['fid'];
}

$folders=array();
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$folders[$rsrow[0]]=$rsrow[1];
}
if (!empty($folders) && $fk_folder_id!=_FOLDER_OUTBOX_ && $fk_folder_id!=_FOLDER_SPAMBOX_) {
	$tpl->set_var('folder_options',vector2options($folders));
}

switch ($fk_folder_id) {

	case _FOLDER_INBOX_:
		$where.=" AND `del`='$del'";
		$tpl->set_var('inbox_options',true);
		break;

	case _FOLDER_TRASH_:
		$mailbox_name='Trash';
		$del=1;
		$where.=" AND `del`='$del'";
		$tpl->set_var('inbox_options',true);
		break;

	case _FOLDER_OUTBOX_:
		$mailbox_name='Outbox';
		$from="`{$dbtable_prefix}user_outbox`";
		$is_read='';
		$tpl->set_var('outbox_options',true);
		break;

	case _FOLDER_SPAMBOX_:
		$mailbox_name='Spambox';
		$from="`{$dbtable_prefix}user_spambox`";
		$tpl->set_var('inbox_options',true);
		break;

	default:
		$mailbox_name=$folders[$fk_folder_id];
		$where.=" AND `del`='$del'";
		$tpl->set_var('inbox_options',true);
		break;

}

if (isset($fk_folder_id) && ($fk_folder_id)>=_FOLDER_INBOX_) {
	 $where.=" AND `fk_folder_id`='$fk_folder_id'";
}

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$mails=array();
if (!empty($totalrows)) {
	$query="SELECT `mail_id`,".$is_read."`_user_other` as `other`,`subject`,UNIX_TIMESTAMP(`date_sent`) as `date_sent`,`message_type` FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_sent']=strftime($_user_settings['datetime_format'],$rsrow['date_sent']+$_user_settings['time_offset']);
		$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$rsrow['is_read']=(empty($rsrow['is_read'])) ? 'mail_not_read'.$rsrow['message_type'] : 'mail_read'.$rsrow['message_type'];
		$mails[]=$rsrow;
	}
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}

$tpl->set_file('content','mailbox.html');
$tpl->set_loop('mails',$mails);
$tpl->set_var('mailbox_name',$mailbox_name);
$tpl->set_var('mailbox_id',$fk_folder_id);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('mails');

if (is_file('mailbox_left.php')) {
	include 'mailbox_left.php';
}
$tplvars['title']='Read your messages';     // translate
include 'frame.php';
?>
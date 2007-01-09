<?php
/******************************************************************************
newdsb
===============================================================================
File:                       inbox.php
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
$fk_folder_id=0;
if (isset($_GET['fid']) && !empty($_GET['fid'])) {
	$fk_folder_id=(int)$_GET['fid'];
	$mailbox_name=get_user_folder_name($fk_folder_id,$_SESSION['user']['user_id']);
}
$del=0;
if (isset($_GET['del']) && !empty($_GET['del'])) {
	$del=1;
	$mailbox_name='Trash';     // translate
	$fk_folder_id='trash';
}

$from="`{$dbtable_prefix}user_inbox` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id_from`=b.`fk_user_id`";
$where="a.`fk_user_id`='".$_SESSION['user']['user_id']."' AND a.`del`='$del'";
if (isset($fk_folder_id)) {
	 $where.=" AND a.`fk_folder_id`='$fk_folder_id'";
}

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$mails=array();
$folders=array();
if (!empty($totalrows)) {
	$query="SELECT a.`mail_id`,a.`is_read`,a.`_user_from` as `from`,a.`subject`,UNIX_TIMESTAMP(a.`date_sent`) as `date_sent`,a.`message_type`,b.`fk_user_id` as `from_id` FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_sent']=strftime($_user_settings['datetime_format'],$rsrow['date_sent']+$_user_settings['time_offset']);
		$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$rsrow['is_read']=(empty($rsrow['is_read'])) ? 'mail_not_read'.$rsrow['message_type'] : 'mail_read'.$rsrow['message_type'];
		$mails[]=$rsrow;
	}
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}

$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$folders[$rsrow[0]]=$rsrow[1];
}
if (!empty($folders)) {
	$tpl->set_var('folder_options',vector2options($folders));
}

$tpl->set_file('content','inbox.html');
$tpl->set_loop('mails',$mails);
$tpl->set_var('mailbox_name',$mailbox_name);
$tpl->set_var('mailbox_id',$fk_folder_id);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('mails');

if (is_file('inbox_left.php')) {
	include 'inbox_left.php';
}
$tplvars['title']='Read your messages';
include 'frame.php';
?>
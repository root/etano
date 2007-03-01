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

$message_types=array(_MESS_MESS_=>'mail',_MESS_FLIRT_=>'flirt',_MESS_SYSTEM_=>'system');

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
unset($moveto_folders[_FOLDER_TRASH_]);
unset($moveto_folders[$fid]);

$from="`{$dbtable_prefix}user_inbox`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

switch ($fid) {

	case _FOLDER_OUTBOX_:
		$from="`{$dbtable_prefix}user_outbox`";
		$tpl->set_var('is_outbox',true);
		break;

	case _FOLDER_SPAMBOX_:
		$from="`{$dbtable_prefix}user_spambox`";
		break;

	case _FOLDER_INBOX_:
	case _FOLDER_TRASH_:
	default:
		$where.=" AND `fk_folder_id`='$fid'";
		break;

}

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	$query="SELECT `mail_id`,`is_read`,`_user_other` as `user_other`,`subject`,UNIX_TIMESTAMP(`date_sent`) as `date_sent`,`message_type` FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_sent']=strftime($_user_settings['date_format'],$rsrow['date_sent']+$_user_settings['time_offset']);
		$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$rsrow['is_read']=(!empty($rsrow['is_read'])) ? 'read' : 'not_read';
		$rsrow['message_type']=$message_types[$rsrow['message_type']];
		if ($rsrow['message_type']==_MESS_SYSTEM_) {
			$rsrow['user_other']='SYSTEM';     // translate
		}
		$loop[]=$rsrow;
	}
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}

$tpl->set_file('content','mailbox.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('mailbox_name',$my_folders[$fid]);
$tpl->set_var('fid',$fid);
$tpl->set_var('folder_options',vector2options($moveto_folders));
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');

if (is_file('mailbox_left.php')) {
	include 'mailbox_left.php';
}
$tplvars['title']='Read your messages';     // translate
$tplvars['page_title']=$my_folders[$fid];
$tplvars['page']='mailbox';
$tplvars['css']='mailbox.css';
include 'frame.php';
?>
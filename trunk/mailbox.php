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

$message_types=array(MESS_MESS=>'mail',MESS_FLIRT=>'flirt',MESS_SYSTEM=>'system');

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

$my_folders=array(FOLDER_INBOX=>'INBOX',FOLDER_OUTBOX=>'OUTBOX',FOLDER_TRASH=>'Trash',FOLDER_SPAMBOX=>'SPAMBOX'); // translate this
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' ORDER BY `folder` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$my_folders[$rsrow[0]]=sanitize_and_format($rsrow[1],TYPE_STRING,$__html2format[HTML_TEXTFIELD]);
}

$fid=FOLDER_INBOX;
if (isset($_GET['fid']) && !empty($_GET['fid']) && isset($my_folders[$_GET['fid']])) {
	$fid=(int)$_GET['fid'];
}
$moveto_folders=$my_folders;
unset($moveto_folders[FOLDER_SPAMBOX]);
unset($moveto_folders[FOLDER_OUTBOX]);
unset($moveto_folders[FOLDER_TRASH]);
unset($moveto_folders[$fid]);

$from="`{$dbtable_prefix}user_inbox`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

switch ($fid) {

	case FOLDER_OUTBOX:
		$from="`{$dbtable_prefix}user_outbox`";
		$tpl->set_var('is_outbox',true);
		break;

	case FOLDER_SPAMBOX:
		$from="`{$dbtable_prefix}user_spambox`";
		break;

	case FOLDER_TRASH:
		$where.=" AND `fk_folder_id`=".FOLDER_INBOX." AND `del`=1";
		break;

	case FOLDER_INBOX:
	default:
		$where.=" AND `fk_folder_id`='$fid' AND `del`=0";
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
		if ($rsrow['message_type']==MESS_SYSTEM) {
			$rsrow['user_other']='SYSTEM';     // translate
		}
		$loop[]=$rsrow;
	}
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$return='mailbox.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$return.='?'.$_SERVER['QUERY_STRING'];
}
$tpl->set_file('content','mailbox.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('mailbox_name',$my_folders[$fid]);
$tpl->set_var('fid',$fid);
$tpl->set_var('folder_options',vector2options($moveto_folders));
$tpl->set_var('return',rawurlencode($return));
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');

$tplvars['title']='Read your messages';     // translate
$tplvars['page_title']=$my_folders[$fid];
$tplvars['page']='mailbox';
$tplvars['css']='mailbox.css';
if (is_file('mailbox_left.php')) {
	include 'mailbox_left.php';
}
include 'frame.php';
?>
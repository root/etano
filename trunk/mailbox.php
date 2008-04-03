<?php
/******************************************************************************
Etano
===============================================================================
File:                       mailbox.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('inbox');

$message_types=array(MESS_MESS=>'mail',MESS_FLIRT=>'flirt',MESS_SYSTEM=>'system');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
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

$my_folders=array(FOLDER_INBOX=>$GLOBALS['_lang'][110],FOLDER_OUTBOX=>$GLOBALS['_lang'][111],FOLDER_TRASH=>$GLOBALS['_lang'][112],FOLDER_SPAMBOX=>$GLOBALS['_lang'][113]);
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' ORDER BY `folder` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$my_folders[$rsrow[0]]=$rsrow[1];
}

$fid=FOLDER_INBOX;
if (!empty($_GET['fid']) && isset($my_folders[$_GET['fid']])) {
	$fid=(int)$_GET['fid'];
}
$moveto_folders=$my_folders;
unset($moveto_folders[FOLDER_SPAMBOX]);
unset($moveto_folders[FOLDER_OUTBOX]);
unset($moveto_folders[FOLDER_TRASH]);
unset($moveto_folders[$fid]);
$my_folders=sanitize_and_format($my_folders,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$from="`{$dbtable_prefix}user_inbox`";
$where="`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";

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
		$where.=" AND `fk_folder_id`=$fid AND `del`=0";
		break;

}

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>=$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `mail_id`,`is_read`,`_user_other` as `user_other`,`subject`,UNIX_TIMESTAMP(`date_sent`) as `date_sent`,`message_type` FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['date_sent']>$page_last_modified_time) {
			$page_last_modified_time=$rsrow['date_sent'];
		}
		$rsrow['date_sent']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['date_format'],$rsrow['date_sent']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
// no need to sanitize
//		$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['is_read']=(!empty($rsrow['is_read'])) ? 'read' : 'not_read';
		if ($rsrow['message_type']==MESS_SYSTEM && empty($rsrow['user_other'])) {
			$rsrow['user_other']=$GLOBALS['_lang'][135];
		}
		$rsrow['message_type']=$message_types[$rsrow['message_type']];
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
unset($loop);

$tplvars['title']=$my_folders[$fid];
$tplvars['page_title']=$my_folders[$fid];
$tplvars['page']='mailbox';
$tplvars['css']='mailbox.css';
if (is_file('mailbox_left.php')) {
	include 'mailbox_left.php';
}
include 'frame.php';

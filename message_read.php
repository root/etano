<?php
/******************************************************************************
Etano
===============================================================================
File:                       message_read.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/tables/user_inbox.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('auth');	// allow every member for now. Finer checking is made below

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$content='';
if (!empty($_GET['mail_id']) && isset($_GET['fid'])) {
	$output=$user_inbox_default['defaults'];
	$output['mail_id']=(int)$_GET['mail_id'];
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);

	$my_folders=array(FOLDER_INBOX=>$GLOBALS['_lang'][110],FOLDER_OUTBOX=>$GLOBALS['_lang'][111],FOLDER_TRASH=>$GLOBALS['_lang'][112],FOLDER_SPAMBOX=>$GLOBALS['_lang'][113]);
	$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_row($res)) {
		$my_folders[$rsrow[0]]=$rsrow[1];
	}

	$output['fid']=FOLDER_INBOX;
	if (!empty($_GET['fid']) && isset($my_folders[$_GET['fid']])) {
		$output['fid']=(int)$_GET['fid'];
	}
	$moveto_folders=$my_folders;
	unset($moveto_folders[FOLDER_SPAMBOX]);
	unset($moveto_folders[FOLDER_OUTBOX]);
	unset($moveto_folders[$output['fid']]);
	$output['moveto_folders']=vector2options($moveto_folders);
	$my_folders=sanitize_and_format($my_folders,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

	$mailbox_table='inbox';
	$where="a.`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND a.`mail_id`=".$output['mail_id'];

	switch ($output['fid']) {
		case FOLDER_INBOX:
			$tpl->set_var('spam_controls',true);
			break;

		case FOLDER_TRASH:
			break;

		case FOLDER_OUTBOX:
			$mailbox_table='outbox';
			$tpl->set_var('is_outbox',true);
			break;

		case FOLDER_SPAMBOX:
			$mailbox_table='spambox';
			break;

		default:
			$tpl->set_var('spam_controls',true);
			break;

	}

	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_sent`) as `date_sent`,b.`fk_user_id` as `other_id`,b.`_photo` as `photo` FROM `{$dbtable_prefix}user_{$mailbox_table}` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id_other`=b.`fk_user_id` WHERE $where LIMIT 1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['date_sent']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$output['date_sent']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
// it was saved sanitized so no need to resanitize here
//		$output['subject']=sanitize_and_format($output['subject'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

		switch ($output['message_type']) {

			case MESS_MESS:
// it was saved sanitized so no need to resanitize here
//				$output['message_body']=sanitize_and_format($output['message_body'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				check_login_member('message_read');
				$output['message_body']=nl2br($output['message_body']);
				break;

			case MESS_FLIRT:
				check_login_member('flirt_read');
				break;

			case MESS_SYSTEM:
				// check_login_member('auth'); this check was made at the begining
				if (empty($output['_user_other'])) {
					$output['_user_other']=$GLOBALS['_lang'][135];
				}
				$tpl->set_var('spam_controls',false);
				break;

		}
		$output['message_body']=text2smilies(bbcode2html($output['message_body']));
		if (empty($output['photo'])) {
			$output['photo']='no_photo.gif';
		}
		if (empty($output['other_id'])) {
			unset($output['other_id']);
		} else {
			require _BASEPATH_.'/includes/network_functions.inc.php';
			if (is_network_member($_SESSION[_LICENSE_KEY_]['user']['user_id'],$output['other_id'],NET_BLOCK)) {
				$output['is_blocked']=true;
			}
			$output['net_block']=NET_BLOCK;
		}
		$output['mailbox_name']=$my_folders[$output['fid']];

		$output['return2me']='message_read.php';
		if (!empty($_SERVER['QUERY_STRING'])) {
			$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
		}
		$output['return2me']=rawurlencode($output['return2me']);
		$tpl->set_file('content','message_read.html');
		$tpl->set_var('output',$output);
		$tpl->process('content','content',TPL_OPTIONAL);
		if ($output['is_read']==0) {
			$query="UPDATE `{$dbtable_prefix}user_{$mailbox_table}` SET `is_read`=1 WHERE `mail_id`=".$output['mail_id']." AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][5];
		redirect2page('mailbox.php');
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][5];
	redirect2page('mailbox.php');
}

$tplvars['title']=$GLOBALS['_lang'][136];
$tplvars['page_title']=$GLOBALS['_lang'][136];
$tplvars['page']='message_read';
$tplvars['css']='message_read.css';
if (is_file('message_read_left.php')) {
	include 'message_read_left.php';
}
include 'frame.php';

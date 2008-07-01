<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/message_proxy.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/tables/user_spambox.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';

$error=false;
$topass=array();
$nextpage='mailbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['mail_id']=sanitize_and_format_gpc($_POST,'mail_id',TYPE_INT,0,0);
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if ($_POST['act']=='del') {
		check_login_member('inbox');
		$input['fid']=sanitize_and_format($_POST['fid'],TYPE_INT,0,0);
		$num_messages=0;
		if (!empty($input['mail_id'])) {
			$num_messages=1;
			if (is_array($input['mail_id'])) {
				$num_messages=count($input['mail_id']);
				$input['mail_id']=join("','",array_keys($input['mail_id']));
			}

			switch ($input['fid']) {

				case FOLDER_TRASH:
					$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `del`=1 AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					break;

				case FOLDER_OUTBOX:
					$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					break;

				case FOLDER_SPAMBOX:
					$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					break;

				default:
					$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`=0,`del`=1 WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					break;

			}
		}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf($GLOBALS['_lang'][73],$num_messages);
	} elseif ($_POST['act']=='move') {
		check_login_member('inbox');
		$input['fid']=sanitize_and_format_gpc($_POST,'fid',TYPE_INT,0,0);
		$input['moveto_fid']=sanitize_and_format_gpc($_POST,'moveto_fid',TYPE_INT,0,0);
		$num_messages=0;
		if (!empty($input['mail_id'])) {
			$num_messages=1;
			if (is_array($input['mail_id'])) {
				$num_messages=count($input['mail_id']);
				$input['mail_id']=join("','",array_keys($input['mail_id']));
			}
			if ($input['fid']>0 || $input['fid']==FOLDER_INBOX || $input['fid']==FOLDER_TRASH) {
				if ($input['moveto_fid']>0 || $input['moveto_fid']==FOLDER_INBOX) {	// user_inbox to user_inbox
					$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`=".$input['moveto_fid'].", `del`=0 WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
				if ($input['fid']==FOLDER_TRASH && ($input['moveto_fid']>0 || $input['moveto_fid']==FOLDER_INBOX)) {
				}
			} elseif ($input['fid']==FOLDER_SPAMBOX) {
				if ($input['moveto_fid']>0 || $input['moveto_fid']==FOLDER_INBOX) {	// user_spambox to user_inbox
					$query="INSERT INTO `{$dbtable_prefix}user_inbox` (`is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`,`fk_folder_id`,`del`) SELECT `is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`,'".$input['moveto_fid']."',0 FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
			}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf($GLOBALS['_lang'][74],$num_messages);
	} elseif ($_POST['act']=='spam') {	// user_inbox to user_spambox
		check_login_member('inbox');
		$query="INSERT INTO `{$dbtable_prefix}user_spambox` (`is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type`) SELECT `is_read`,`fk_user_id`,`fk_user_id_other`,`_user_other`,`subject`,`message_body`,`date_sent`,`message_type` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('".$input['mail_id']."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="SELECT `fk_user_id_other` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id` IN ('".$input['mail_id']."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		// set the 'spam_sent' property of the sender(s)
		$spammers=array();
		while ($rsrow=mysql_fetch_row($res)) {
			if (!isset($spammers[$rsrow[0]])) {
				$spammers[$rsrow[0]]=1;
			} else {
				++$spammers[$rsrow[0]];
			}
		}
		foreach ($spammers as $k=>$v) {
			update_stats($k,'spam_sent',$v);
		}
	} elseif ($_POST['act']=='reply') {
		check_login_member('message_reply');
		$nextpage='message_send.php?mail_id='.$input['mail_id'];
		if (!empty($input['return'])) {
			$nextpage.='&return='.rawurlencode($input['return']);
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

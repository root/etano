<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/message_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/tables/queue_message.inc.php';
require_once '../includes/tables/user_outbox.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';

if (is_file(_BASEPATH_.'/events/processors/message_send.php')) {
	include_once _BASEPATH_.'/events/processors/message_send.php';
}

$error=false;
$topass=array();
$nextpage='mailbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	unset($queue_message_default['types']['mail_id']);	// no id cause we're not editing anything
	foreach ($queue_message_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v] | FORMAT_HTML2TEXT_FULL,$queue_message_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}
	if (!empty($_POST['refnum']) && isset($_SESSION[_LICENSE_KEY_]['user'][$_POST['refnum']])) {
		$input['refnum']=$_SESSION[_LICENSE_KEY_]['user'][$_POST['refnum']];
	} else {
		$input['refnum']='message_write';
	}
	check_login_member($input['refnum']);

// check for input errors
	if (empty($input['fk_user_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][75];
	}
	if (empty($input['subject'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][196];
	}
	if (empty($input['message_body'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][197];
	}

	if (!$error) {
		// sender of the message: me
		$input['fk_user_id_other']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		$input['_user_other']=$_SESSION[_LICENSE_KEY_]['user']['user'];
		$query="INSERT INTO `{$dbtable_prefix}queue_message` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($queue_message_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (isset($_on_before_insert)) {
			for ($i=0;isset($_on_before_insert[$i]);++$i) {
				call_user_func($_on_before_insert[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		// save the message in my outbox
		$input['fk_user_id_other']=$input['fk_user_id'];
		$input['fk_user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		$input['_user_other']=get_user_by_userid($input['fk_user_id_other']);
		$query="INSERT INTO `{$dbtable_prefix}user_outbox` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($user_outbox_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		update_stats($_SESSION[_LICENSE_KEY_]['user']['user_id'],'mess_sent',1);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=$GLOBALS['_lang'][198];
		if (isset($_on_after_insert)) {
			for ($i=0;isset($_on_after_insert[$i]);++$i) {
				call_user_func($_on_after_insert[$i]);
			}
		}
		unset($_SESSION[_LICENSE_KEY_]['user'][$_POST['refnum']]);
	} else {
		$nextpage='message_send.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['message_body']=addslashes_mq($_POST['message_body']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				call_user_func($_on_error[$i]);
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

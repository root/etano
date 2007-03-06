<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/message_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
require_once '../includes/tables/queue_message.inc.php';
require_once '../includes/tables/user_outbox.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$error=false;
$topass=array();
$nextpage='mailbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($queue_message_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$queue_message_default['defaults'][$k]);
	}
	if (isset($_POST['return'])) {
		$input['return']=rawurldecode(sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['fk_user_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Message not sent because there was no recipient specified';
	}
	if (empty($input['subject'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the message subject';
	}
	if (empty($input['message_body'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the message body';
	}

	if (!$error) {
		// sender of the message: me
		$input['fk_user_id_other']=$_SESSION['user']['user_id'];
		$input['_user_other']=$_SESSION['user']['user'];
		$query="INSERT INTO `{$dbtable_prefix}queue_message` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($queue_message_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		// save the message in my outbox
		$input['fk_user_id_other']=$input['fk_user_id'];
		$input['fk_user_id']=$_SESSION['user']['user_id'];
		$input['_user_other']=get_user_by_userid($input['fk_user_id_other']);
		$query="INSERT INTO `{$dbtable_prefix}user_outbox` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($user_outbox_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Message sent.';
	} else {
		$nextpage='message_send.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['message_body']=addslashes_mq($_POST['message_body']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
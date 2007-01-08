<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/message_send.php
$Revision: 85 $
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='inbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($queue_message_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$queue_message_default['defaults'][$k]);
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
		$input['fk_user_id_from']=$_SESSION['user']['user_id'];
		$input['_user_from']=$_SESSION['user']['user'];
		$query="INSERT INTO `{$dbtable_prefix}queue_message` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($queue_message_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Message sent.';
	} else {
		$nextpage='message_send.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
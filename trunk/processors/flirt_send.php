<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/flirt_send.php
$Revision: 25 $
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
$qs='';
$qs_sep='';
$topass=array();
$nextpage='flirt_send.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['flirt']=(isset($_POST['flirt']) && !empty($_POST['flirt'])) ? (int)$_POST['flirt'] : 0;
// get the input we need and sanitize it
	foreach ($queue_message_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$queue_message_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['fk_user_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Flirt not sent because there was no recipient specified';
	} else {
		$qs.=$qs_sep.'uid='.$input['fk_user_id'];
		$qs_sep='&';
	}
	if (empty($input['flirt'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please choose a flirt message to send';
	} else {
		$query="SELECT `flirt_text` FROM `{$dbtable_prefix}flirts` WHERE `flirt_id`='".$input['flirt']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$input['message_body']=addslashes_mq(mysql_result($res,0,0));
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='This flirt message does not exist.';
		}
	}

	if (!$error) {
		$input['fk_user_id_other']=$_SESSION['user']['user_id'];
		$input['_user_other']=$_SESSION['user']['user'];
		$input['subject']='You have received a flirt from '.$_SESSION['user']['user'];
		$input['message_type']=_MESS_FLIRT_;
		$query="INSERT INTO `{$dbtable_prefix}queue_message` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($queue_message_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
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
		$topass['message']['text']='Flirt sent.';
	} else {
// 		you must replace '\r' and '\n' strings with <enter> in all textareas like this:
//		$input['x']=preg_replace(array('/([^\\\])\\\n/','/([^\\\])\\\r/'),array("$1\n","$1"),$input['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
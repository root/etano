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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='mailbox.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['flirt_id']=sanitize_and_format_gpc($_POST,'flirt_id',TYPE_INT,0,0);
	$input['fk_user_id']=sanitize_and_format_gpc($_POST,'fk_user_id',TYPE_INT,0,0);
	if (isset($_POST['return']) && !empty($_POST['return'])) {
		$input['return']=rawurldecode(sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['fk_user_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Flirt not sent because there was no receiver specified';
	}
	if (empty($input['flirt_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a flirt to send';
	}

	if (!$error) {
		$query="SELECT `flirt_text` FROM `{$dbtable_prefix}flirts` WHERE `flirt_id`='".$input['flirt_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$input['message_body']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__html2format[TEXT_DB2DB]);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Could not find flirt in database. Please select another flirt';
		}
	}

	if (!$error) {
		$input['fk_user_id_other']=$_SESSION['user']['user_id'];
		$input['_user_other']=$_SESSION['user']['user'];
		$input['subject']=sprintf('%s sent you a flirt',$_SESSION['user']['user']);	// translate
		$input['message_type']=MESS_FLIRT;
		$query="INSERT INTO `{$dbtable_prefix}queue_message` SET `date_sent`='".gmdate('YmdHis')."'";
		foreach ($queue_message_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		update_stats($_SESSION['user']['user_id'],'flirts_sent',1);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Flirt sent.';
	} else {
		$nextpage='flirt_send.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/flirt_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/tables/queue_message.inc.php';
check_login_member('flirt_send');

if (is_file(_BASEPATH_.'/events/processors/flirt_send.php')) {
	include_once _BASEPATH_.'/events/processors/flirt_send.php';
}

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
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
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
			$input['message_body']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__field2format[TEXT_DB2DB]);
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
		if (isset($_on_before_insert)) {
			for ($i=0;isset($_on_before_insert[$i]);++$i) {
				eval($_on_before_insert[$i].'();');
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		update_stats($_SESSION['user']['user_id'],'flirts_sent',1);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Flirt sent.';
		if (isset($_on_after_insert)) {
			for ($i=0;isset($_on_after_insert[$i]);++$i) {
				eval($_on_after_insert[$i].'();');
			}
		}
	} else {
		$nextpage='flirt_send.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				eval($_on_error[$i].'();');
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

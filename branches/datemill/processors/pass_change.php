<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/pass_change.php
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

if (is_file(_BASEPATH_.'/events/processors/pass_change.php')) {
	include_once _BASEPATH_.'/events/processors/pass_change.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_settings.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['pass2']=sanitize_and_format_gpc($_POST,'pass2',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	if ($input['pass']!=$input['pass2']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]="The passwords do not match.";
		$input['error_pass']='red_border';
	}
	if (empty($input['pass'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]="Please enter the password.";
		$input['error_pass']='red_border';
	}

	if (!$error) {
		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `".USER_ACCOUNT_PASS."`=".PASSWORD_ENC_FUNC."('".$input['pass']."') WHERE `".USER_ACCOUNT_ID."`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (isset($_on_before_update)) {
			for ($i=0;isset($_on_before_update[$i]);++$i) {
				call_user_func($_on_before_update[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Password changed.';     // translate
		if (isset($_on_after_update)) {
			for ($i=0;isset($_on_after_update[$i]);++$i) {
				call_user_func($_on_after_update[$i]);
			}
		}
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				call_user_func($_on_error[$i]);
			}
		}
	}
}
redirect2page($nextpage,$topass,$qs);

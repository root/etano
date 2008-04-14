<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/forgot_pass_change.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/pass_change.inc.php';

if (is_file(_BASEPATH_.'/events/processors/forgot_pass_change.php')) {
	include _BASEPATH_.'/events/processors/forgot_pass_change.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='forgot_pass_change.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['uid']=sanitize_and_format_gpc($_POST,'uid',TYPE_INT,0,0);
	$input['user']=strtolower(sanitize_and_format_gpc($_POST,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
	$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['pass2']=sanitize_and_format_gpc($_POST,'pass2',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['secret']=sanitize_and_format_gpc($_POST,'secret',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	if ($input['pass']!=$input['pass2']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]=$GLOBALS['_lang'][56];
		$input['error_pass']='red_border';
	}
	if (empty($input['pass'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]=$GLOBALS['_lang'][57];
		$input['error_pass']='red_border';
	}
	if (empty($input['uid']) || empty($input['secret'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]=$GLOBALS['_lang'][58];
	}
	if (get_site_option('use_captcha','core')) {
		$captcha=sanitize_and_format_gpc($_POST,'captcha',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		if (!$error && (!isset($_SESSION['captcha_word']) || strcasecmp($captcha,$_SESSION['captcha_word'])!=0)) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][24];
			$input['error_captcha']='red_border';
		}
	}
	unset($_SESSION['captcha_word']);

	if (!$error) {
		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `".USER_ACCOUNT_PASS."`=".PASSWORD_ENC_FUNC."('".$input['pass']."'),`temp_pass`='".gen_pass(7)."' WHERE `".USER_ACCOUNT_ID."`=".$input['uid']." AND `".USER_ACCOUNT_USER."`='".$input['user']."' AND `temp_pass`='".$input['secret']."'";
		if (isset($_on_before_update)) {
			for ($i=0;isset($_on_before_update[$i]);++$i) {
				call_user_func($_on_before_update[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_affected_rows()) {
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=$GLOBALS['_lang'][59];
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$GLOBALS['_lang'][60];
		}
		$nextpage='login.php';
		if (isset($_on_after_update)) {
			for ($i=0;isset($_on_after_update[$i]);++$i) {
				call_user_func($_on_after_update[$i]);
			}
		}
	}
	if ($error) {
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

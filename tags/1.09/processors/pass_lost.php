<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/pass_lost.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/pass_change.inc.php';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='pass_lost.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['email']=strtolower(sanitize_and_format_gpc($_POST,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
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
		$query="SELECT `".USER_ACCOUNT_ID."` as `uid`,`".USER_ACCOUNT_USER."` as `user`,`email` FROM `".USER_ACCOUNTS_TABLE."` WHERE `email`='".$input['email']."' LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$input=mysql_fetch_assoc($res);
			$input['temp_pass']=md5(gen_pass(6));
			$input['ipaddr']=$_SERVER['REMOTE_ADDR'];
			$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `temp_pass`='".$input['temp_pass']."' WHERE `".USER_ACCOUNT_ID."`=".$input['uid'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			send_template_email($input['email'],sprintf($GLOBALS['_lang'][225],_SITENAME_),'pass_reset.html',get_my_skin(),$input);
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=$GLOBALS['_lang'][89];
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$GLOBALS['_lang'][90];
		}
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);

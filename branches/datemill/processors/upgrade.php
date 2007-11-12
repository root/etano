<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/upgrade.php
$Revision: 400 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='upgrade_verif.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['name']=sanitize_and_format_gpc($_POST,'name',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['email']=sanitize_and_format_gpc($_POST,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['txn']=sanitize_and_format_gpc($_POST,'txn',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['old_url']=sanitize_and_format_gpc($_POST,'old_url',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['old_url']=trim($input['old_url'],'/');

	if (empty($input['name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter your name.';
	}
	if (empty($input['email'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter your email address where we can contact you back.';
	}
	if (empty($input['txn'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter your DSB purchase transaction number.';
	}
	if (empty($input['old_url']) || $input['old_url']=='http://' || substr($input['old_url'],0,7)!='http://') {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the address of your DSB site.';
	}
	$domain=explode('/',substr($input['old_url'],7));
	$domain=$domain[0];
	if ($domain==gethostbyname($domain)) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid site entered.';
	}

	if (!$error) {
		$key=strtolower(gen_pass(10));
		$query="INSERT INTO `dsb2_upgrades` SET `name`='".$input['name']."',`email`='".$input['email']."',`txn`='".$input['txn']."',`old_url`='".$input['old_url']."',`key`='$key'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$input['upid']=mysql_insert_id();
		$_SESSION[_LICENSE_KEY_]['user']['upkey']=$key;
		$_SESSION[_LICENSE_KEY_]['user']['upsite']=$input['old_url'];
		$qs.=$qs_sep.'upid='.$input['upid'];
		$qs_sep='&';
	} else {
		$nextpage='upgrade.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);

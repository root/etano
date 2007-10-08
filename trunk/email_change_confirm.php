<?php
/******************************************************************************
Etano
===============================================================================
File:                       pass_change_confirm.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$uid=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$email=sanitize_and_format_gpc($_GET,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

if (!empty($uid) && !empty($email)) {
	$req_email=get_user_settings($uid,'','new_email');
	if ($req_email==$email) {
		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `email`='$email' WHERE `".USER_ACCOUNT_ID."`=$uid LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}user_settings2` WHERE `fk_user_id`=$uid AND `config_option`='new_email'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Your email address has been changed.';
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid email change request.';
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Invalid email change request.';
}
redirect2page('info.php',$topass);

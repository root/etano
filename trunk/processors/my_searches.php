<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/my_searches.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
check_login_member(14);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_searches.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['is_default']=sanitize_and_format_gpc($_POST,'is_default',TYPE_INT,0,0);
	$input['alert']=sanitize_and_format_gpc($_POST,'alert',TYPE_INT,0,array());
	// make sure $input['alert'] is an array
	if (!is_array($input['alert']) && !empty($input['alert'])) {
		$input['alert']=array($input['alert']);
	}

	if (!$error) {
		$query="UPDATE `{$dbtable_prefix}user_searches` SET `is_default`=0 WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!empty($input['is_default'])) {
			$query="UPDATE `{$dbtable_prefix}user_searches` SET `is_default`=1 WHERE `search_id`='".$input['is_default']."' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$query="UPDATE `{$dbtable_prefix}user_searches` SET `alert`=0 WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!empty($input['alert'])) {
			$query="UPDATE `{$dbtable_prefix}user_searches` SET `alert`=1 WHERE `search_id` IN ('".join("','",$input['alert'])."') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Ok';     // translate
	} else {
		$nextpage='my_searches.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
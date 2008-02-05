<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/my_searches.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/my_searches.inc.php';
check_login_member('save_searches');

if (is_file(_BASEPATH_.'/events/processors/my_searches.php')) {
	include_once _BASEPATH_.'/events/processors/my_searches.php';
}

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
		if (isset($_on_before_update)) {
			for ($i=0;isset($_on_before_update[$i]);++$i) {
				call_user_func($_on_before_update[$i]);
			}
		}
		$query="UPDATE `{$dbtable_prefix}user_searches` SET `is_default`=0,`alert`=0 WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!empty($input['is_default'])) {
			$query="UPDATE `{$dbtable_prefix}user_searches` SET `is_default`=1 WHERE `search_id`=".$input['is_default']." AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		if (!empty($input['alert'])) {
			$query="UPDATE `{$dbtable_prefix}user_searches` SET `alert`=1 WHERE `search_id` IN ('".join("','",$input['alert'])."') AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=$GLOBALS['_lang'][77];
		if (isset($_on_after_update)) {
			for ($i=0;isset($_on_after_update[$i]);++$i) {
				call_user_func($_on_after_update[$i]);
			}
		}
	} else {
		$nextpage='my_searches.php';
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

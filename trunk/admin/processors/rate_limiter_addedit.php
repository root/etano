<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/rate_limiter_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/logs.inc.php';
require_once '../../includes/tables/rate_limiter.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/rate_limiter.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($rate_limiter_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$rate_limiter_default['defaults'][$k]);
	}

	$input['error_message']=sanitize_and_format_gpc($_POST,'error_message',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

// check for input errors
	if (empty($input['m_value'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a membership!';
		$input['error_fk_m_id']='red_border';
	}
	if (empty($input['level_code'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a level!';
		$input['error_level_code']='red_border';
	}
	if (empty($input['limit'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a limit!';
		$input['error_limit']='red_border';
	}
	if (empty($input['interval'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a time interval for this limit!';
		$input['error_interval']='red_border';
	}
	if (empty($input['punishment'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a punishment for when this limit is reached!';
		$input['error_interval']='red_border';
	}
	if (empty($input['error_message'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the error message for this limit!';
		$input['error_error_message']='red_border';
	}

	if (!$error) {
		$default_skin_code=get_default_skin_code();
		if (!empty($input['rate_id'])) {
			$query="UPDATE `{$dbtable_prefix}rate_limiter` SET ";
			foreach ($rate_limiter_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `rate_id`='".$input['rate_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="REPLACE INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['error_message']."',`fk_lk_id`='".$input['fk_lk_id_error_message']."',`skin`='$default_skin_code'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Limit changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Error message for a limit',`lk_use`='".LK_MESSAGE."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_error_message']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` (`lang_value`,`fk_lk_id`,`skin`) VALUES ('".$input['error_message']."','".$input['fk_lk_id_error_message']."','$default_skin_code')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			$query="INSERT INTO `{$dbtable_prefix}rate_limiter` SET ";
			foreach ($rate_limiter_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Limit added.';
		}
		regenerate_langstrings_array();
	} else {
		$nextpage='admin/rate_limiter_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
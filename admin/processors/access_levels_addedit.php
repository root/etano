<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/access_levels_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/access_levels.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/access_levels.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($access_levels_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$access_levels_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['level_code'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the level code!';
		$input['error_level_code']='red_border';
	}

	if ($input['level_code']=='auth' || $input['level_code']=='all') {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='The "auth" and "all" level codes are reserved and cannot be used. Please choose another code!';
		$input['error_level_code']='red_border';
	}

	unset($input['level']);

	if (!$error) {
		if (!empty($input['level_id'])) {
			$query="UPDATE IGNORE `{$dbtable_prefix}access_levels` SET ";
			foreach ($access_levels_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `level_id`='".$input['level_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Level changed.';
			}
		} else {
			$query="INSERT IGNORE INTO `{$dbtable_prefix}access_levels` SET ";
			foreach ($access_levels_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Level added.';
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='This level code already exists. Please enter a unique code!';
				$input['error_level_code']='red_border';
			}
		}
	}

	if ($error) {
		$nextpage='admin/access_levels_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
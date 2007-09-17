<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/folders_addedit.php
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
require_once '../includes/tables/user_folders.inc.php';
check_login_member('manage_folders');

if (is_file(_BASEPATH_.'/events/processors/folders_addedit.php')) {
	include_once _BASEPATH_.'/events/processors/folders_addedit.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='folders.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($user_folders_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_folders_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];

	if (!$error) {
		if (!empty($input['folder_id'])) {
			$query="UPDATE IGNORE `{$dbtable_prefix}user_folders` SET ";
			foreach ($user_folders_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `folder_id`=".$input['folder_id']." AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (isset($_on_before_update)) {
				for ($i=0;isset($_on_before_update[$i]);++$i) {
					call_user_func($_on_before_update[$i]);
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Folder renamed.';     // translate
			} else {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Folder not changed. This folder name already exists.';     // translate
			}
			if (isset($_on_after_update)) {
				for ($i=0;isset($_on_after_update[$i]);++$i) {
					call_user_func($_on_after_update[$i]);
				}
			}
		} else {
			unset($input['folder_id']);
			$query="INSERT IGNORE INTO `{$dbtable_prefix}user_folders` SET ";
			unset($input['folder_id']);
			foreach ($user_folders_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (isset($_on_before_insert)) {
				for ($i=0;isset($_on_before_insert[$i]);++$i) {
					call_user_func($_on_before_insert[$i]);
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['folder_id']=mysql_insert_id();

			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Folder added.';     // translate
			} else {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Folder not added. This folder name already exists.';     // translate
			}
			if (isset($_on_after_insert)) {
				for ($i=0;isset($_on_after_insert[$i]);++$i) {
					call_user_func($_on_after_insert[$i]);
				}
			}
		}
	} else {
		$nextpage='folders_addedit.php';
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

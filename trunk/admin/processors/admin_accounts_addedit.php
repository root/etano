<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/admin_accounts_addedit.php
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
require_once '../../includes/tables/admin_accounts.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/admin_accounts.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($admin_accounts_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$admin_accounts_default['defaults'][$k]);
	}
	$pass2=sanitize_and_format_gpc($_POST,'pass2',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['change_pass']=sanitize_and_format_gpc($_POST,'change_pass',TYPE_INT,0,0);

// check for input errors
	if (empty($input['user'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the user';
		$input['error_user']='red_border';
	}
	if (empty($input['name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the name';
		$input['error_name']='red_border';
	}
	if ($input['change_pass'] || empty($input['admin_id'])) {
		if (empty($input['pass'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter the password';
			$input['error_pass']='red_border';
		}
		if (strlen($input['pass'])<4 || strlen($input['pass'])>20) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='The password must have between 4 and 20 chars';
			$input['error_pass']='red_border';
		}
		if ($input['pass']!=$pass2) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Passwords do not match';
			$input['error_pass']='red_border';
		}
	}

	if (!$error) {
		if ($input['change_pass']) {
			$input['pass']=md5($input['pass']);
		} else {
			unset($input['pass']);
		}
		$input['user']=strtolower($input['user']);
		if (!empty($input['admin_id'])) {
			$query="UPDATE `{$dbtable_prefix}admin_accounts` SET ";
			foreach ($admin_accounts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `admin_id`='".$input['admin_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Account info changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}admin_accounts` SET ";
			foreach ($admin_accounts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Account added.';
		}
	} else {
		$nextpage='admin/admin_accounts_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/subscriptions_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/subscriptions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/subscriptions.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($subscriptions_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$subscriptions_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['subscr_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the short description';
		$input['error_subscr_name']='red_border';
	}
	if (empty($input['m_value_from'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a starting membership!';
		$input['error_m_value_from']='red_border';
	}
	if (empty($input['m_value_to'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select an ending membership!';
		$input['error_m_value_to']='red_border';
	}
	if ($input['m_value_from']==$input['m_value_to']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='The ending membership must be different from the starting membership!';
		$input['error_m_value_to']='red_border';
	}

	if (!$error) {
		if (!empty($input['subscr_id'])) {
			$query="UPDATE `{$dbtable_prefix}subscriptions` SET ";
			foreach ($subscriptions_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `subscr_id`='".$input['subscr_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Subscription changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}subscriptions` SET ";
			foreach ($subscriptions_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Subscription added.';
		}
	} else {
		$nextpage='admin/subscriptions_addedit.php';
// 		you must replace '\r' and '\n' strings with <enter> in all textareas like this:
//		$input['x']=preg_replace(array('/([^\\\])\\\n/','/([^\\\])\\\r/'),array("$1\n","$1"),$input['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
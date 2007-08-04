<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/subscriptions_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/subscriptions.inc.php';
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
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$subscriptions_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['subscr_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the short description';
		$input['error_subscr_name']='red_border';
	}
	if (empty($input['m_value_to'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the membership this subscription upgrades to!';
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
			$query.=" WHERE `subscr_id`=".$input['subscr_id'];
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
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/auto_subscriptions_addedit.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../../includes/tables/auto_subscriptions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/auto_subscriptions.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($auto_subscriptions_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$auto_subscriptions_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['fk_subscr_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the subscription to be assigned';
		$input['error_subscr_name']='red_border';
	}

	if (!$error) {
		if (!empty($input['asubscr_id'])) {
			$query="UPDATE `{$dbtable_prefix}auto_subscriptions` SET ";
			foreach ($auto_subscriptions_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `asubscr_id`='".$input['asubscr_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Subscription assignment changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}auto_subscriptions` SET ";
			foreach ($auto_subscriptions_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Subscription assignment added.';
		}
	} else {
		$nextpage='admin/auto_subscriptions_addedit.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
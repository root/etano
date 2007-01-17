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
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/rate_limiter.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
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
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$rate_limiter_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['m_value'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a membership!';
		$input['error_fk_m_id']='red_border';
	}
	if (empty($input['fk_level_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a level!';
		$input['error_fk_level_id']='red_border';
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

	if (!$error) {
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
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Limit changed.';
		} else {
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
	} else {
		$nextpage='admin/rate_limiter_addedit.php';
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
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
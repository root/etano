<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/loc_states_addedit.php
$Revision: 81 $
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
require_once '../../includes/tables/loc_states.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/loc_states.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($states_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$states_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['state'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the state name';
		$input['error_state']='red_border';
	}

	if (!$error) {
		if (!empty($input['state_id'])) {
			$query="UPDATE `{$dbtable_prefix}loc_states` SET ";
			foreach ($states_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `state_id`='".$input['state_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='State info changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}loc_states` SET ";
			foreach ($states_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `{$dbtable_prefix}loc_countries` SET `num_states`=`num_states`+1 WHERE `country_id`='".$input['fk_country_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='State added.';
		}
	} else {
		$nextpage='admin/loc_states_addedit.php';
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
	if (isset($_POST['co'])) {
		$qs.=$qs_sep.'co='.$_POST['co'];
		$qs_sep='&';
	}
	if (isset($_POST['cr'])) {
		$qs.=$qs_sep.'cr='.$_POST['cr'];
		$qs_sep='&';
	}
	$qs.=$qs_sep.'country_id='.$input['fk_country_id'];
}
redirect2page($nextpage,$topass,$qs);
?>
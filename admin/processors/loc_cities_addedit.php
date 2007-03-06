<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/loc_cities_addedit.php
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
require_once '../../includes/tables/loc_cities.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/loc_cities.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($cities_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$cities_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['city'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the city name';
		$input['error_city']='red_border';
	}

	if (!$error) {
		if (!empty($input['city_id'])) {
			$query="UPDATE `{$dbtable_prefix}loc_cities` SET ";
			foreach ($cities_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `city_id`='".$input['city_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='City info changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}loc_cities` SET ";
			foreach ($cities_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `{$dbtable_prefix}loc_states` SET `num_cities`=`num_cities`+1 WHERE `state_id`='".$input['fk_state_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='City added.';
		}
	} else {
		$nextpage='admin/loc_cities_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	$qs.=$qs_sep.'state_id='.$input['fk_state_id'];
	$qs_sep='&';
	$qs.=$qs_sep.'country_id='.$input['fk_country_id'];
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
	}
	if (isset($_POST['so'])) {
		$qs.=$qs_sep.'so='.$_POST['so'];
	}
	if (isset($_POST['sr'])) {
		$qs.=$qs_sep.'sr='.$_POST['sr'];
	}
	if (isset($_POST['co'])) {
		$qs.=$qs_sep.'co='.$_POST['co'];
	}
	if (isset($_POST['cr'])) {
		$qs.=$qs_sep.'cr='.$_POST['cr'];
	}
}
redirect2page($nextpage,$topass,$qs);
?>
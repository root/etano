<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/site_skins_addedit.php
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
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/site_skins.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/site_skins.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($site_skins_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$site_skins_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['skin_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the skin name!';
		$input['error_skin_name']='red_border';
	}

	$query="SELECT `skin_id` FROM `{$dbtable_prefix}site_skins` WHERE `skin_name`='".$input['skin_name']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)!=$input['skin_id']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='This skin name already exists! Please enter a unique name.';
		$input['error_skin_name']='red_border';
	}

	if (!$error) {
		if (!empty($input['skin_id'])) {
			unset($input['skin_code']);
			$query="UPDATE `{$dbtable_prefix}site_skins` SET ";
			foreach ($input as $k=>$v) {
				$query.="`$k`='$v',";
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `skin_id`='".$input['skin_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Skin settings saved.';
		}
	} else {
		$nextpage='admin/site_skins_addedit.php';
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
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/site_skins_addedit.php
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

	$query="SELECT `fk_module_code` FROM `{$dbtable_prefix}site_options3` WHERE `config_option`='skin_name' AND `config_value`='".$input['skin_name']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)!=$input['fk_module_code']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='This skin name already exists! Please enter a unique name.';
		$input['error_skin_name']='red_border';
	}

	if (!$error) {
		if (!empty($input['fk_module_code'])) {
			$fk_module_code=$input['fk_module_code'];
			unset($input['fk_module_code']);
			unset($input['skin_dir']);
			foreach ($input as $k=>$v) {
				set_site_option($k,$fk_module_code,$v);
			}

//			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Skin settings saved.';
		}
	} else {
		$nextpage='admin/site_skins_addedit.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
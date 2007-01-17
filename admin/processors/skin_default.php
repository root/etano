<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/skin_default.php
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$module_code=sanitize_and_format_gpc($_GET,'module_code',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');

$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='"._MODULE_SKIN_."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$skin_modules=array();
for ($i=0;$i<mysql_num_rows($res);++$i) {
	$skin_modules[]=mysql_result($res,$i,0);
}

$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`=0 WHERE `fk_module_code` IN ('".join("','",$skin_modules)."') AND `config_option`='is_default'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`=1 WHERE `fk_module_code`='$module_code' AND `config_option`='is_default'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Skin updated.';

redirect2page('admin/site_skins.php',$topass,$qs);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/skin_default.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$module_code=sanitize_and_format_gpc($_GET,'module_code',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`=".MODULE_SKIN;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$skin_modules=array();
for ($i=0;$i<mysql_num_rows($res);++$i) {
	$skin_modules[]=mysql_result($res,$i,0);
}

$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`=0 WHERE `fk_module_code` IN ('".join("','",$skin_modules)."') AND `config_option`='is_default'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`=1 WHERE `fk_module_code`='$module_code' AND `config_option`='is_default'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$_SESSION[_LICENSE_KEY_]['admin']['def_skin']=get_default_skin_dir();

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Skin updated.';

redirect2page('admin/site_skins.php',$topass,$qs);

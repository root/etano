<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_skins_delete.php
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

$query="SELECT count(*) FROM `{$dbtable_prefix}modules` WHERE `module_type`=".MODULE_SKIN;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)>1) {
	$config=get_site_option(array('is_default','skin_dir'),$module_code);

	$query="DELETE FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	$query="DELETE FROM `{$dbtable_prefix}modules` WHERE `module_code`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	if (!empty($config['is_default'])) {
		$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`=".MODULE_SKIN." LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$module_code=mysql_result($res,0,0);
			set_site_option('is_default',$module_code,1);
		}
	}

	require_once '../../includes/classes/fileop.class.php';
	$fileop=new fileop();
	$fileop->delete(_BASEPATH_.'/skins_site/'.$config['skin_dir']);

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Skin deleted.';
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='You can\'t delete the last skin of the site!';
}
redirect2page('admin/site_skins.php',$topass,$qs);

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/site_skins_delete.php
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

$config=get_site_option(array('is_default','skin_dir'),$module_code);

$query="DELETE FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code`='$module_code'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}modules` WHERE `module_code`='$module_code'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

if (!empty($config['is_default'])) {
	$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='"._MODULE_SKIN_."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$module_code=mysql_result($res,0,0);
		set_site_option('is_default',$module_code,1);
	}
}

require_once '../../includes/classes/modman.class.php';
$modman=new modman();
$modman->fileop->delete(_BASEPATH_.'/skins/'.$config['skin_dir']);

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Skin deleted.';

redirect2page('admin/site_skins.php',$topass,$qs);
?>
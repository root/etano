<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/site_skins_addedit.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/site_skins.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$site_skins=$site_skins_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$site_skins=$_SESSION['topass']['input'];
} elseif (isset($_GET['module_code']) && !empty($_GET['module_code'])) {
	$module_code=sanitize_and_format($_GET['module_code'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	$site_skins=get_site_option(array(),$module_code);
	$site_skins=sanitize_and_format($site_skins,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	$site_skins['fk_module_code']=$module_code;
}

$site_skins['fk_locale_id']=dbtable2options("`{$dbtable_prefix}locales`",'`locale_id`','`locale_name`','`locale_name`',$site_skins['fk_locale_id']);

$tpl->set_file('content','site_skins_addedit.html');
$tpl->set_var('site_skins',$site_skins);
$tpl->process('content','content');

$tplvars['title']='Site Skins';
include 'frame.php';
?>
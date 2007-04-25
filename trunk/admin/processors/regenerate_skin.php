<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/regenerate_skin.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$skin=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

// check for input errors
	if (empty($skin)) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No skin specified for regeneration! Please select a skin.';
	}

	if (!$error) {
// categories for profile.html first
		$tpl->set_file('pwrow','static/pwrow.html');
		$categs=array();
		foreach ($_pfields as $i=>$v) {

		}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='All files in the \''.$skin.'\' skin were regenerated. You can start using this skin now';
	}
}
redirect2page('admin/site_skins.php',$topass,$qs);
?>
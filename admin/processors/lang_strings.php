<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/lang_strings.php
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
require_once '../../includes/tables/rate_limiter.inc.php';
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
	$input['skin']=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['lang_strings']=sanitize_and_format_gpc($_POST,'lang_strings',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');


// check for input errors
	if (empty($input['skin'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No skin selected!';
	}

	if (!$error) {
		foreach ($input['lang_strings'] as $k=>$v) {
			$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='$v' WHERE `fk_lk_id`='$k' AND `skin`='".$input['skin']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!mysql_affected_rows()) {
				$query="INSERT IGNORE INTO `{$dbtable_prefix}lang_strings` SET `fk_lk_id`='$k',`skin`='".$input['skin']."',`lang_value`='$v'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}

		regenerate_langstrings_array();

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Strings updated.';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/lang_strings.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/site_skins.php';
//$nextpage='admin/lang_strings.php';
$qs='#bottom';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['skin']=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['lang_strings']=sanitize_and_format_gpc($_POST,'lang_strings',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');


// check for input errors
	if (empty($input['skin'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No skin selected!';
	}

	if (!$error) {
		foreach ($input['lang_strings'] as $k=>$v) {
			$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='$v' WHERE `fk_lk_id`=$k AND `skin`='".$input['skin']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!mysql_affected_rows()) {
				$query="INSERT IGNORE INTO `{$dbtable_prefix}lang_strings` SET `fk_lk_id`=$k,`skin`='".$input['skin']."',`lang_value`='$v'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}

		regenerate_langstrings_array();

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Strings updated.';
	}
}
redirect2page($nextpage,$topass,$qs);

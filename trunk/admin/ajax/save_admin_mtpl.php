<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/save_admin_mtpl.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$amtpl_type=sanitize_and_format_gpc($_POST,'amtpl_type',TYPE_INT,0,0);
	$amtpl_id=sanitize_and_format_gpc($_POST,'amtpl_id',TYPE_INT,0,0);
	$amtpl_name=sanitize_and_format_gpc($_POST,'amtpl_name',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$subject=sanitize_and_format_gpc($_POST,'subject',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$message_body=sanitize_and_format_gpc($_POST,'message_body',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	if (!empty($subject) && !empty($message_body)) {
		if (!empty($amtpl_id)) {
			$query="UPDATE `{$dbtable_prefix}admin_mtpls` SET `subject`='$subject',`message_body`='$message_body' WHERE `amtpl_id`='$amtpl_id'";
		} else {
			$query="INSERT INTO `{$dbtable_prefix}admin_mtpls` SET `amtpl_name`='$amtpl_name',`subject`='$subject',`message_body`='$message_body',`amtpl_type`='$amtpl_type'";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$output.='Template saved succesfully';
	}
}
echo $output;
?>
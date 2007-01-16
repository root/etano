<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/ajax/field_values.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/sessions.inc.php';
require_once dirname(__FILE__).'/../../includes/vars.inc.php';
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$optype=sanitize_and_format_gpc($_POST,'optype',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$val=sanitize_and_format_gpc($_POST,'val',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$lk_id=sanitize_and_format_gpc($_POST,'lk_id',TYPE_INT,0,0);
	switch ($optype) {

		case 'add':
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`="._HTML_TEXTFIELD_.",`lk_diz`='Field value',`lk_use`='".LK_FIELD."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$lk_id=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='$val',`fk_lk_id`='$lk_id',`skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$output=$lk_id;
			break;

		case 'edit':
			$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='$val' WHERE `fk_lk_id`='$lk_id' AND `skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$output=$lk_id;
			break;

		case 'del':
			$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='$lk_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id`='$lk_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$output=$lk_id;
			break;

	}
}
echo $output;
?>
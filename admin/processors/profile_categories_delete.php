<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_categories_delete.php
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

$qs='';
$qs_sep='';
$topass=array();
$pcat_id=isset($_GET['pcat_id']) ? (int)$_GET['pcat_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `fk_lk_id_pcat` FROM `{$dbtable_prefix}profile_categories` WHERE `pcat_id`=$pcat_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$lk_id=mysql_result($res,0,0);
	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`=$lk_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id`=$lk_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}profile_categories` WHERE `pcat_id`=$pcat_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

//	regenerate_langstrings_array();
//	regenerate_fields_array();

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Category deleted.';

// trigger generate_fields
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/profile_categories.php';
}
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_fields_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$pfield_id=isset($_GET['pfield_id']) ? (int)$_GET['pfield_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `field_type`,`dbfield`,`fk_lk_id_label`,`fk_lk_id_search`,`fk_lk_id_help`,`accepted_values` FROM `{$dbtable_prefix}profile_fields` WHERE `pfield_id`='$pfield_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$rsrow=mysql_fetch_assoc($res);
	$key_ids=array();
	if ($rsrow['field_type']==FIELD_SELECT || $rsrow['field_type']==FIELD_CHECKBOX || $rsrow['field_type']==FIELD_CHECKBOX_LARGE) {
		$key_ids=explode('|',substr($rsrow['accepted_values'],1,-1));
	}
	$key_ids[]=$rsrow['fk_lk_id_label'];
	$key_ids[]=$rsrow['fk_lk_id_search'];
	$key_ids[]=$rsrow['fk_lk_id_help'];
	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN ('".join("','",$key_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id` IN ('".join("','",$key_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}profile_fields` WHERE `pfield_id`='$pfield_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if ($rsrow['field_type']==FIELD_LOCATION) {
		$query="ALTER TABLE `{$dbtable_prefix}user_profiles` DROP `".$rsrow['dbfield']."_country`,DROP `".$rsrow['dbfield']."_state`,DROP `".$rsrow['dbfield']."_city`,DROP `".$rsrow['dbfield']."_zip`";
	} else {
		$query="ALTER TABLE `{$dbtable_prefix}user_profiles` DROP `".$rsrow['dbfield']."`";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	regenerate_fields_array();
	regenerate_langstrings_array();

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Field deleted.';
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/profile_fields.php';
}
redirect2page($nextpage,$topass,'',true);
?>
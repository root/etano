<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_fields_delete.php
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
$pfield_id=isset($_GET['pfield_id']) ? (int)$_GET['pfield_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `field_type`,`dbfield`,`fk_lk_id_label`,`fk_lk_id_search`,`fk_lk_id_help`,`custom_config` FROM `{$dbtable_prefix}profile_fields2` WHERE `pfield_id`=$pfield_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$rsrow=mysql_fetch_assoc($res);
	$key_ids=array();
	$key_ids[]=$rsrow['fk_lk_id_label'];
	$key_ids[]=$rsrow['fk_lk_id_search'];
	$key_ids[]=$rsrow['fk_lk_id_help'];
	// for field_select and field_mchecks we need to extract the language keys used for the accepted values
	// the method below is VERY ugly and hardcoded but should do the trick for now.
	if ($rsrow['field_type']=='field_select' || $rsrow['field_type']=='field_mchecks') {
		$m=array();
		preg_match_all("/\['lang'\]\[(\d)+?\]/",$rsrow['custom_config'],$m);
		$key_ids=array_merge($key_ids,$m);
	}
	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN ('".join("','",$key_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id` IN ('".join("','",$key_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}profile_fields2` WHERE `pfield_id`=$pfield_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$temp=new $rsrow['field_type'];
	$temp=$temp->query_drop($rsrow['dbfield']);
	if (!empty($temp)) {
		$query="ALTER TABLE `{$dbtable_prefix}user_profiles`".$temp;
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

//	regenerate_langstrings_array();
//	regenerate_fields_array();

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Field deleted.';
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/profile_fields.php';
}
redirect2page($nextpage,$topass,'',true);

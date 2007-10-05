<?php
// this works ok now but we'd better not use it because in future more field types might appear and if we don't
// update this script then it will break the sites.

$jobs[]='orphaned_lk';

function orphaned_lk() {
	global $dbtable_prefix;
	$lk_ids=array();

	// lk_ids from profile_categories
	$query="SELECT `fk_lk_id_pcat` FROM `{$dbtable_prefix}profile_categories`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$lk_ids[]=mysql_result($res,$i,0);
	}

	// lk_ids from profile_fields
	$query="SELECT `fk_lk_id_label`,`fk_lk_id_search`,`fk_lk_id_help` FROM `{$dbtable_prefix}profile_fields`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$lk_ids[]=$rsrow['fk_lk_id_label'];
		$lk_ids[]=$rsrow['fk_lk_id_search'];
		$lk_ids[]=$rsrow['fk_lk_id_help'];
	}

	// lk_ids from profile_fields.accepted_values
	$query="SELECT `accepted_values` FROM `{$dbtable_prefix}profile_fields` WHERE `field_type` IN (".FIELD_SELECT.','.FIELD_CHECKBOX_LARGE.") AND `accepted_values`<>''";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0,$count=mysql_num_rows($res);$i<$count;++$i) {
		$accval=mysql_result($res,$i,0);
		$lk_ids=array_merge($lk_ids,explode('|',substr($accval,1,-1)));
	}

	// lk_ids from rate_limiter
	$query="SELECT `fk_lk_id_error_message` FROM `{$dbtable_prefix}rate_limiter`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$lk_ids[]=mysql_result($res,$i,0);
	}

	// lk_ids from site_bans
	$query="SELECT `fk_lk_id_reason` FROM `{$dbtable_prefix}site_bans`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$lk_ids[]=mysql_result($res,$i,0);
	}

	$query="SELECT `lk_id` FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id` NOT IN (".join(',',$lk_ids).") AND `lk_use` IN (".LK_FIELD.','.LK_SITE.")";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$lk_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$lk_ids[]=mysql_result($res,$i,0);
	}

	if (!empty($lk_ids)) {
		$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN (".join(',',$lk_ids).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id` IN (".join(',',$lk_ids).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		regenerate_langstrings_array();
	}

	return true;
}

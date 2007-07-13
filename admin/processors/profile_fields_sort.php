<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_fields_sort.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$pfield_id=isset($_GET['pfield_id']) ? (int)$_GET['pfield_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `order_num` FROM `{$dbtable_prefix}profile_fields` WHERE `pfield_id`='$pfield_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$mynum=mysql_result($res,0,0);
if ($_GET['move']=='d') {
	$query="SELECT min(`order_num`) FROM `{$dbtable_prefix}profile_fields` WHERE `order_num`>'$mynum'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$downnum=mysql_result($res,0,0);
		$query="SELECT `pfield_id` FROM `{$dbtable_prefix}profile_fields` WHERE `order_num`='$downnum'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$downpfield_id=mysql_result($res,0,0);
			$query="UPDATE `{$dbtable_prefix}profile_fields` SET `order_num`='$downnum' WHERE `pfield_id`='$pfield_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `{$dbtable_prefix}profile_fields` SET `order_num`='$mynum' WHERE `pfield_id`='$downpfield_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
} elseif ($_GET['move']=='u') {
	$query="SELECT max(`order_num`) FROM `{$dbtable_prefix}profile_fields` WHERE `order_num`<'$mynum'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$upnum=mysql_result($res,0,0);
		$query="SELECT `pfield_id` FROM `{$dbtable_prefix}profile_fields` WHERE `order_num`='$upnum'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$uppfield_id=mysql_result($res,0,0);
			$query="UPDATE `{$dbtable_prefix}profile_fields` SET `order_num`='$upnum' WHERE `pfield_id`='$pfield_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="UPDATE `{$dbtable_prefix}profile_fields` SET `order_num`='$mynum' WHERE `pfield_id`='$uppfield_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
}

regenerate_fields_array();
regenerate_langstrings_array();

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/profile_fields.php';
}
redirect2page($nextpage,$topass,'',true);

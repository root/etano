<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/profile_fields_sort.php
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
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$pfield_id=(int)$_GET['pfield_id'];

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

if (isset($_POST['o'])) {
	$qs.=$qs_sep.'o='.$_POST['o'];
	$qs_sep='&';
}
if (isset($_POST['r'])) {
	$qs.=$qs_sep.'r='.$_POST['r'];
	$qs_sep='&';
}
if (isset($_POST['ob'])) {
	$qs.=$qs_sep.'ob='.$_POST['ob'];
	$qs_sep='&';
}
if (isset($_POST['od'])) {
	$qs.=$qs_sep.'od='.$_POST['od'];
	$qs_sep='&';
}
redirect2page('admin/profile_fields.php',$topass,$qs);
?>
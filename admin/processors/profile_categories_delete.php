<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/profile_categories_delete.php
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$pcat_id=isset($_GET['pcat_id']) ? (int)$_GET['pcat_id'] : 0;

$query="SELECT `fk_lk_id_pcat` FROM `{$dbtable_prefix}profile_categories` WHERE `pcat_id`='$pcat_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$lk_id=mysql_result($res,0,0);
	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='$lk_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id`='$lk_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}profile_categories` WHERE `pcat_id`='$pcat_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	regenerate_fields_array();
	regenerate_langstrings_array();

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Category deleted.';

// trigger generate_fields
}

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
redirect2page('admin/profile_categories.php',$topass,$qs);
?>
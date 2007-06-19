<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/loc_states_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$state_id=isset($_GET['state_id']) ? (int)$_GET['state_id'] : 0;
$country_id=isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}loc_cities` WHERE `fk_state_id`='$state_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}loc_states` WHERE `state_id`='$state_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="UPDATE `{$dbtable_prefix}loc_countries` SET `num_states`=`num_states`-1 WHERE `country_id`='$country_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='State and all its cities deleted.';

if (isset($_GET['o'])) {
	$qs.=$qs_sep.'o='.$_GET['o'];
	$qs_sep='&';
}
if (isset($_GET['r'])) {
	$qs.=$qs_sep.'r='.$_GET['r'];
	$qs_sep='&';
}
if (isset($_GET['co'])) {
	$qs.=$qs_sep.'co='.$_GET['co'];
	$qs_sep='&';
}
if (isset($_GET['cr'])) {
	$qs.=$qs_sep.'cr='.$_GET['cr'];
	$qs_sep='&';
}
$qs.=$qs_sep.'country_id='.$country_id;
redirect2page('admin/loc_states.php',$topass,$qs);
?>
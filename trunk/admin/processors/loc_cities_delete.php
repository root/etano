<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/loc_cities_delete.php
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

$qs='';
$qs_sep='';
$topass=array();
$city_id=isset($_GET['city_id']) ? (int)$_GET['city_id'] : 0;
$state_id=isset($_GET['state_id']) ? (int)$_GET['state_id'] : 0;
$country_id=isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}loc_cities` WHERE `city_id`='$city_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="UPDATE `{$dbtable_prefix}loc_states` SET `num_cities`=`num_cities`-1 WHERE `state_id`='$state_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='City deleted.';

$qs.=$qs_sep.'state_id='.$state_id;
$qs_sep='&';
$qs.=$qs_sep.'country_id='.$country_id;
if (isset($_GET['o'])) {
	$qs.=$qs_sep.'o='.$_GET['o'];
}
if (isset($_GET['r'])) {
	$qs.=$qs_sep.'r='.$_GET['r'];
}
if (isset($_GET['so'])) {
	$qs.=$qs_sep.'so='.$_GET['so'];
}
if (isset($_GET['sr'])) {
	$qs.=$qs_sep.'sr='.$_GET['sr'];
}
if (isset($_GET['co'])) {
	$qs.=$qs_sep.'co='.$_GET['co'];
}
if (isset($_GET['cr'])) {
	$qs.=$qs_sep.'cr='.$_GET['cr'];
}
redirect2page('admin/loc_cities.php',$topass,$qs);

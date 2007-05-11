<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_cities_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/loc_cities.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$city=$cities_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$city=$_SESSION['topass']['input'];
	$query="SELECT a.`state`,b.`country` FROM `{$dbtable_prefix}loc_states` a,`{$dbtable_prefix}loc_countries` b WHERE a.`state_id`='".$city['fk_state_id']."' AND a.`fk_country_id`=b.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$city['state']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$city['country']=sanitize_and_format(mysql_result($res,0,1),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
} elseif (isset($_GET['city_id']) && !empty($_GET['city_id'])) {
	$city_id=(int)$_GET['city_id'];
	$query="SELECT a.`city_id`,a.`city`,a.`latitude`,a.`longitude`,a.`fk_state_id`,a.`fk_country_id`,b.`state`,c.`country` FROM `{$dbtable_prefix}loc_cities` a,`{$dbtable_prefix}loc_states` b,`{$dbtable_prefix}loc_countries` c WHERE a.`city_id`='$city_id' AND a.`fk_state_id`=b.`state_id` AND a.`fk_country_id`=c.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$city=mysql_fetch_assoc($res);
		$city['city']=sanitize_and_format($city['city'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$city['state']=sanitize_and_format($city['state'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$city['country']=sanitize_and_format($city['country'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	}
} elseif (isset($_GET['state_id']) && !empty($_GET['state_id']) && isset($_GET['country_id']) && !empty($_GET['country_id'])) {
	$city['fk_state_id']=(int)$_GET['state_id'];
	$city['fk_country_id']=(int)$_GET['country_id'];
	$query="SELECT a.`state`,b.`country` FROM `{$dbtable_prefix}loc_states` a,`{$dbtable_prefix}loc_countries` b WHERE a.`state_id`='".$city['fk_state_id']."' AND a.`fk_country_id`=b.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$city['state']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$city['country']=sanitize_and_format(mysql_result($res,0,1),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
}

$tpl->set_file('content','loc_cities_addedit.html');
$tpl->set_var('city',$city);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['so'])) {
	$tpl->set_var('so',$_GET['so']);
}
if (isset($_GET['sr'])) {
	$tpl->set_var('sr',$_GET['sr']);
}
if (isset($_GET['co'])) {
	$tpl->set_var('co',$_GET['co']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('cr',$_GET['cr']);
}
$tpl->process('content','content');

$tplvars['title']='Location Management: Cities';
$tplvars['page']='loc_cities_addedit';
include 'frame.php';
?>
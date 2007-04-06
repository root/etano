<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_zipcodes_addedit.php
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
require_once '../includes/tables/loc_zipcodes.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$zipcode=$zipcodes_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$zipcode=$_SESSION['topass']['input'];
	$query="SELECT a.`city`,b.`state`,c.`country` FROM `{$dbtable_prefix}loc_cities` a,`{$dbtable_prefix}loc_states` b,`{$dbtable_prefix}loc_countries` c WHERE a.`city_id`='".$zipcode['fk_city_id']."' AND a.`fk_state_id`=b.`state_id` AND a.`fk_country_id`=c.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$zipcode['city']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$zipcode['state']=sanitize_and_format(mysql_result($res,0,1),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$zipcode['country']=sanitize_and_format(mysql_result($res,0,2),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
} elseif (isset($_GET['zip_id']) && !empty($_GET['zip_id'])) {
	$zip_id=(int)$_GET['zip_id'];
	$query="SELECT a.`zip_id`,a.`zipcode`,a.`latitude`,a.`longitude`,a.`fk_city_id`,a.`fk_state_id`,a.`fk_country_id`,b.`city`,c.`state`,d.`country` FROM `{$dbtable_prefix}loc_zips` a,`{$dbtable_prefix}loc_cities` b,`{$dbtable_prefix}loc_states` c,`{$dbtable_prefix}loc_countries` d WHERE a.`zip_id`='$zip_id' AND a.`fk_city_id`=b.`city_id` AND a.`fk_state_id`=c.`state_id` AND a.`fk_country_id`=d.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$zipcode=mysql_fetch_assoc($res);
		$zipcode['city']=sanitize_and_format($zipcode['city'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$zipcode['state']=sanitize_and_format($zipcode['state'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$zipcode['country']=sanitize_and_format($zipcode['country'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	}
} elseif (isset($_GET['city_id']) && !empty($_GET['city_id']) && isset($_GET['state_id']) && !empty($_GET['state_id']) && isset($_GET['country_id']) && !empty($_GET['country_id'])) {
	$zipcode['fk_city_id']=(int)$_GET['city_id'];
	$zipcode['fk_state_id']=(int)$_GET['state_id'];
	$zipcode['fk_country_id']=(int)$_GET['country_id'];
	$query="SELECT a.`city`,b.`state`,c.`country` FROM `{$dbtable_prefix}loc_cities` a,`{$dbtable_prefix}loc_states` b,`{$dbtable_prefix}loc_countries` c WHERE a.`city_id`='".$zipcode['fk_city_id']."' AND a.`fk_state_id`=b.`state_id` AND a.`fk_country_id`=c.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$zipcode['city']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$zipcode['state']=sanitize_and_format(mysql_result($res,0,1),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$zipcode['country']=sanitize_and_format(mysql_result($res,0,2),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
}

$tpl->set_file('content','loc_zipcodes_addedit.html');
$tpl->set_var('zipcode',$zipcode);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['cio'])) {
	$tpl->set_var('cio',$_GET['cio']);
}
if (isset($_GET['cir'])) {
	$tpl->set_var('cir',$_GET['cir']);
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

$tplvars['title']='Location Management: Zipcodes';
include 'frame.php';
?>
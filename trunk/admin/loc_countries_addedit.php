<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_countries_addedit.php
$Revision: 56 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/loc_countries.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$countries=$countries_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$countries=$_SESSION['topass']['input'];
} elseif (isset($_GET['country_id']) && !empty($_GET['country_id'])) {
	$country_id=(int)$_GET['country_id'];
	$query="SELECT `country_id`,`country`,`iso3166`,`prefered_input` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='$country_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$countries=mysql_fetch_assoc($res);
		$countries['country']=sanitize_and_format($countries['country'],TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}

$countries['prefered_input']=vector2options($country_prefered_input,$countries['prefered_input']);

$tpl->set_file('content','loc_countries_addedit.html');
$tpl->set_var('countries',$countries);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
$tpl->process('content','content');

$tplvars['title']='Location Management: Countries';
include 'frame.php';
?>
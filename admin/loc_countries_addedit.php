<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/loc_countries_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/loc_countries.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$countries=$countries_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$countries=$_SESSION['topass']['input'];
} elseif (!empty($_GET['country_id'])) {
	$country_id=(int)$_GET['country_id'];
	$query="SELECT `country_id`,`country`,`iso3166`,`prefered_input` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='$country_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$countries=mysql_fetch_assoc($res);
		$countries['country']=sanitize_and_format($countries['country'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
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
$tplvars['page']='loc_countries_addedit';
include 'frame.php';

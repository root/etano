<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_states_addedit.php
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
require_once 'includes/admin_functions.inc.php';
require_once '../includes/tables/loc_states.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$states=$states_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$states=$_SESSION['topass']['input'];
	$query="SELECT `country` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$states['fk_country_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$states['country']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
} elseif (isset($_GET['state_id']) && !empty($_GET['state_id'])) {
	$state_id=(int)$_GET['state_id'];
	$query="SELECT a.`state_id`,a.`state`,a.`fk_country_id`,b.`country` FROM `{$dbtable_prefix}loc_states` a,`{$dbtable_prefix}loc_countries` b WHERE a.`state_id`='$state_id' AND a.`fk_country_id`=b.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$states=mysql_fetch_assoc($res);
		$states['state']=sanitize_and_format($states['state'],TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
		$states['country']=sanitize_and_format($states['country'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	}
} elseif (isset($_GET['country_id']) && !empty($_GET['country_id'])) {
	$states['fk_country_id']=(int)$_GET['country_id'];
	$query="SELECT `country` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$states['fk_country_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$states['country']=sanitize_and_format(mysql_result($res,0,0),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
}

$tpl->set_file('content','loc_states_addedit.html');
$tpl->set_var('states',$states);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['co'])) {
	$tpl->set_var('co',$_GET['co']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('cr',$_GET['cr']);
}
$tpl->process('content','content');

$tplvars['title']='Location Management: States';
include 'frame.php';
?>
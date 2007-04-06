<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/access_levels_addedit.php
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
require_once '../includes/tables/access_levels.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$access_levels=$access_levels_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$access_levels=$_SESSION['topass']['input'];
} elseif (isset($_GET['level_id']) && !empty($_GET['level_id'])) {
	$level_id=(int)$_GET['level_id'];
	$query="SELECT * FROM `{$dbtable_prefix}access_levels` WHERE `level_id`='$level_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$access_levels=mysql_fetch_assoc($res);
		$access_levels=sanitize_and_format($access_levels,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}

$tpl->set_file('content','access_levels_addedit.html');
$tpl->set_var('access_levels',$access_levels);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['ob'])) {
	$tpl->set_var('ob',$_GET['ob']);
}
if (isset($_GET['od'])) {
	$tpl->set_var('od',$_GET['od']);
}
$tpl->process('content','content');

$tplvars['title']='Access Levels Management';
include 'frame.php';
?>
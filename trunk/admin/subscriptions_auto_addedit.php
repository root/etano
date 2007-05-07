<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/subscriptions_auto_addedit.php
$Revision: 21 $
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
require_once '../includes/tables/subscriptions_auto.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$subscriptions_auto=$subscriptions_auto_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$subscriptions_auto=$_SESSION['topass']['input'];
} elseif (isset($_GET['asubscr_id']) && !empty($_GET['asubscr_id'])) {
	$asubscr_id=(int)$_GET['asubscr_id'];
	$query="SELECT * FROM `{$dbtable_prefix}subscriptions_auto` WHERE `asubscr_id`='$asubscr_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$subscriptions_auto=mysql_fetch_assoc($res);
		$subscriptions_auto=sanitize_and_format($subscriptions_auto,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$subscriptions_auto['fk_subscr_id']=dbtable2options("`{$dbtable_prefix}subscriptions`",'`subscr_id`','`subscr_name`','`subscr_id`',$subscriptions_auto['fk_subscr_id']);
if (empty($subscriptions_auto['dbfield'])) {
	$subscriptions_auto['to_members_1']='checked="checked"';
} else {
	$subscriptions_auto['to_members_2']='checked="checked"';
}
$dbfields=array();
foreach ($_pfields as $pfield_id=>$pfield) {
	if ($pfield['field_type']==FIELD_SELECT) {
		$dbfields[$pfield['dbfield']]=$pfield['label'].' ('.$pfield['dbfield'].')';
	}
}
if (!empty($subscriptions_auto['dbfield'])) {
	$accepted_values=array();
	foreach ($_pfields as $pfield_id=>$pfield) {
		if ($pfield['dbfield']==$subscriptions_auto['dbfield']) {
			$accepted_values=$pfield['accepted_values'];
		}
	}
	$subscriptions_auto['field_value']=vector2options($accepted_values,$subscriptions_auto['field_value']);
}
$subscriptions_auto['dbfield']=vector2options($dbfields,$subscriptions_auto['dbfield']);

$tpl->set_file('content','subscriptions_auto_addedit.html');
$tpl->set_var('subscriptions_auto',$subscriptions_auto);
$tpl->process('content','content');

$tplvars['title']='Auto Subscriptions Management';
$tplvars['css']='subscriptions_auto_addedit.css';
include 'frame.php';
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/auto_subscriptions_addedit.php
$Revision: 85 $
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
require_once '../includes/tables/auto_subscriptions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$auto_subscriptions=$auto_subscriptions_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$auto_subscriptions=$_SESSION['topass']['input'];
} elseif (isset($_GET['asubscr_id']) && !empty($_GET['asubscr_id'])) {
	$asubscr_id=(int)$_GET['asubscr_id'];
	$query="SELECT * FROM `{$dbtable_prefix}auto_subscriptions` WHERE `asubscr_id`='$asubscr_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$auto_subscriptions=mysql_fetch_assoc($res);
		$auto_subscriptions=sanitize_and_format($auto_subscriptions,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}

$auto_subscriptions['fk_subscr_id']=dbtable2options("`{$dbtable_prefix}subscriptions`",'`subscr_id`','`subscr_name`','`subscr_id`',$auto_subscriptions['fk_subscr_id']);
if (empty($auto_subscriptions['dbfield'])) {
	$auto_subscriptions['to_members_1']='checked="checked"';
} else {
	$auto_subscriptions['to_members_2']='checked="checked"';
}
$dbfields=array();
foreach ($_pfields as $pfield_id=>$pfield) {
	if ($pfield['html_type']==_HTML_SELECT_) {
		$dbfields[$pfield['dbfield']]=$pfield['label'].' ('.$pfield['dbfield'].')';
	}
}
if (!empty($auto_subscriptions['dbfield'])) {
	$accepted_values=array();
	foreach ($_pfields as $pfield_id=>$pfield) {
		if ($pfield['dbfield']==$auto_subscriptions['dbfield']) {
			$accepted_values=$pfield['accepted_values'];
		}
	}
	$auto_subscriptions['field_value']=vector2options($accepted_values,$auto_subscriptions['field_value']);
}
$auto_subscriptions['dbfield']=vector2options($dbfields,$auto_subscriptions['dbfield']);

$tpl->set_file('content','auto_subscriptions_addedit.html');
$tpl->set_var('auto_subscriptions',$auto_subscriptions);
$tpl->process('content','content');

$tplvars['title']='Auto Subscriptions Management';
include 'frame.php';
?>
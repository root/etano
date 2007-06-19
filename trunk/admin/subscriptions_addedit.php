<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/subscriptions_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/subscriptions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$subscriptions=$subscriptions_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$subscriptions=$_SESSION['topass']['input'];
} elseif (isset($_GET['subscr_id']) && !empty($_GET['subscr_id'])) {
	$subscr_id=(int)$_GET['subscr_id'];
	$query="SELECT * FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`='$subscr_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$subscriptions=mysql_fetch_assoc($res);
		$subscriptions=sanitize_and_format($subscriptions,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

// we preffer to do this query here rather than dbtable2options to save one dbtable2options query.
$query="SELECT `m_value`,`m_name` FROM `{$dbtable_prefix}memberships` WHERE `m_value`<>1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$memberships=array();
while ($rsrow=mysql_fetch_row($res)) {
	$memberships[$rsrow[0]]=$rsrow[1];
}

$subscriptions['currency']=vector2options($accepted_currencies,$subscriptions['currency']);
$subscriptions['m_value_from']=vector2options($memberships,$subscriptions['m_value_from']);
$subscriptions['m_value_to']=vector2options($memberships,$subscriptions['m_value_to'],array(2));
$subscriptions['is_recurent']=($subscriptions['is_recurent']==1) ? 'checked="checked"' : '';
$subscriptions['is_visible']=($subscriptions['is_visible']==1) ? 'checked="checked"' : '';

$tpl->set_file('content','subscriptions_addedit.html');
$tpl->set_var('subscriptions',$subscriptions);
$tpl->process('content','content');

$tplvars['title']='Subscriptions Management';
$tplvars['css']='subscriptions_addedit.css';
$tplvars['page']='subscriptions_addedit';
include 'frame.php';
?>
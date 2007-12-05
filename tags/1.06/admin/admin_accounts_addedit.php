<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/admin_accounts_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/admin_accounts.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$admin_accounts=$admin_accounts_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$admin_accounts=$_SESSION['topass']['input'];
	if (!empty($admin_accounts['change_pass'])) {
		$admin_accounts['change_pass']='checked="checked"';
	}
} elseif (!empty($_GET['admin_id'])) {
	$admin_id=(int)$_GET['admin_id'];
	$query="SELECT `admin_id`,`user`,`name`,`status`,`dept_id`,`email` FROM `{$dbtable_prefix}admin_accounts` WHERE `admin_id`=$admin_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$admin_accounts=mysql_fetch_assoc($res);
		$admin_accounts['name']=sanitize_and_format($admin_accounts['name'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$admin_accounts['dept_id']=vector2options($accepted_admin_depts,$admin_accounts['dept_id']);
$admin_accounts['status']=vector2options($accepted_astats,$admin_accounts['status'],array(ASTAT_UNVERIFIED));
if (empty($admin_accounts['admin_id'])) {
	$admin_accounts['change_pass']='checked="checked"';
}
$tpl->set_file('content','admin_accounts_addedit.html');
$tpl->set_var('admin_accounts',$admin_accounts);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
$tpl->process('content','content');

$tplvars['title']='Admin Accounts';
$tplvars['page']='admin_accounts_addedit';
include 'frame.php';

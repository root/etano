<?php
/******************************************************************************
Etano
===============================================================================
File:                       subscriptions.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$query="SELECT a.`module_code`,a.`module_name`,a.`module_diz` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_PAYMENT." AND b.`fk_module_code`=a.`module_code` AND b.`config_option`='module_active' AND `config_value`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$active_gateways=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['module_name']='<strong>'.$rsrow['module_name'].'</strong> '.$rsrow['module_diz'];
	$active_gateways[$rsrow['module_code']]=$rsrow['module_name'];
}

$query="SELECT `subscr_id`,`subscr_name`,`subscr_diz` FROM `{$dbtable_prefix}subscriptions` WHERE `is_visible`=1 AND `m_value_from`='".$_SESSION['user']['membership']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$subscriptions=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['subscr_name']='<strong>'.$rsrow['subscr_name'].'</strong> '.$rsrow['subscr_diz'];
	$subscriptions[$rsrow['subscr_id']]=$rsrow['subscr_name'];
}

$tpl->set_file('content','subscriptions.html');
$tpl->set_var('subscriptions',vector2radios($subscriptions,'subscr_id'));
$tpl->set_var('active_gateways',vector2radios($active_gateways,'module_code',4));
$tpl->process('content','content');

$tplvars['title']='Upgrade Your Membership';
$tplvars['page_title']='Upgrade Your Membership';
$tplvars['page']='subscriptions';
$tplvars['css']='subscriptions.css';
if (is_file('subscriptions_left.php')) {
	include 'subscriptions_left.php';
}
include 'frame.php';
?>
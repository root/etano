<?php
/******************************************************************************
newdsb
===============================================================================
File:                       subscriptions.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(3);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$query="SELECT a.`module_code`,a.`module_name`,a.`module_diz` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`="._MODULE_PAYMENT_." AND b.`fk_module_code`=a.`module_code` AND b.`config_option`='module_active' AND `config_value`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$active_gateways=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$rsrow['module_name']='<strong>'.$rsrow['module_name'].'</strong> '.$rsrow['module_diz'];
	$active_gateways[$rsrow['module_code']]=$rsrow['module_name'];
}

$query="SELECT `subscr_id`,`subscr_name`,`subscr_diz` FROM `{$dbtable_prefix}subscriptions` WHERE `is_visible`=1 AND `m_value_from`='".$_SESSION['user']['membership']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$subscriptions=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$rsrow['subscr_name']='<strong>'.$rsrow['subscr_name'].'</strong> '.$rsrow['subscr_diz'];
	$subscriptions[$rsrow['subscr_id']]=$rsrow['subscr_name'];
}

$tpl->set_file('content','subscriptions.html');
$tpl->set_var('subscriptions',vector2radios($subscriptions,'subscr_id'));
$tpl->set_var('active_gateways',vector2radios($active_gateways,'module_code',4));
$tpl->process('content','content');

$tplvars['title']='Upgrade your membership';
include 'frame.php';
?>
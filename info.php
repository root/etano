<?php
/******************************************************************************
Etano
===============================================================================
File:                       info.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
global $tplvars;

$type=isset($_GET['type']) ? $_GET['type'] : '';
$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
switch ($type) {
	case 'signup':
		$template='info_signup.html';
		$output['email']=isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : (isset($_GET['email']) ? $_GET['email'] : '');
		$tplvars['page_title']='Signup successful!';
		$tplvars['page']='info_signup';
		break;

	case 'upgrade':
		$template='info_upgrade.html';
		$tplvars['page_title']='Upgrade';
		$tplvars['page']='info_upgrade';
		break;

	case 'mailsent':
		$template='info_mailsent.html';
		$tplvars['page_title']='Email sent';
		$tplvars['page']='info_mailsent';
		break;

	case 'acctactiv':	// activate account
		$template='info_acctactiv.html';
		$tplvars['page_title']='Activate your account';
		$tplvars['page']='info_acctactiv';
		$output['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
		$output['email']=sanitize_and_format_gpc($_GET,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		break;

	case 'acctok':	// account confirmed
		$template='info_acctok.html';
		$tplvars['page_title']='Account successfully confirmed!';
		$tplvars['page']='info_acctok';
		break;

	case 'access':	// no access to the requested page, show the upgrade options.
		$template='info_access.html';
		$tplvars['page_title']='Subscribe';
		$tplvars['page']='info_acctok';

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

		$output['subscriptions']=vector2radios($subscriptions,'subscr_id');
		$output['active_gateways']=vector2radios($active_gateways,'module_code',4);
		break;

	default:
		$template='info.html';
		$tplvars['page_title']='Message';
		$tplvars['page']='info';

}
$tpl->set_file('content',$template);
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Information';
$tplvars['css']='info.css';
if (is_file('info_left.php')) {
	include 'info_left.php';
}
include 'frame.php';
?>
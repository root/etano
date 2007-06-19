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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
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
		$tplvars['page_title']='Upgrade your account';
		$tplvars['page']='info_acctok';
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
<?php
/******************************************************************************
Etano
===============================================================================
File:                       pass_change.php
$Revision: 91 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/classes/sco_captcha.class.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	unset($_SESSION['topass']['input']);
} else {
	$output['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
	$output['secret']=sanitize_and_format_gpc($_GET,'secret',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
}

if (!empty($output['uid']) && !empty($output['secret'])) {
	$query="SELECT `".USER_ACCOUNT_ID."` FROM ".USER_ACCOUNTS_TABLE." WHERE `".USER_ACCOUNT_ID."`='".$output['uid']."' AND `temp_pass`='".$output['secret']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		if (get_site_option('use_captcha','core')) {
			$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
			$_SESSION['captcha_word']=$c->gen_rnd_string(4);
			$output['rand']=make_seed();
			$output['use_captcha']=true;
		} else {
			unset($output['use_captcha']);
		}
	} else {
		trigger_error('Invalid user');
	}
} else {
	trigger_error('Invalid user');
}

$tpl->set_file('content','pass_change.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Change Password';
$tplvars['page_title']='Change Password';
$tplvars['page']='pass_change';
$tplvars['css']='pass_change.css';
include 'frame.php';
?>
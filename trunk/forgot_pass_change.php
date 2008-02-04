<?php
/******************************************************************************
Etano
===============================================================================
File:                       forgot_pass_change.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/classes/sco_captcha.class.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/pass_change.inc.php';

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
	$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."`=".$output['uid']." AND `temp_pass`='".$output['secret']."'";
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

$output['lang_259']=sanitize_and_format($GLOBALS['_lang'][259],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_265']=sanitize_and_format($GLOBALS['_lang'][265],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_266']=sanitize_and_format($GLOBALS['_lang'][266],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','forgot_pass_change.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']=$GLOBALS['_lang'][224];
$tplvars['page_title']=$GLOBALS['_lang'][224];
$tplvars['page']='forgot_pass_change';
$tplvars['css']='forgot_pass_change.css';
$no_timeout=true;
include 'frame.php';

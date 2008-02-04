<?php
/******************************************************************************
Etano
===============================================================================
File:                       contact.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/contact.inc.php';
check_login_member('contact');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$config=get_site_option(array('use_captcha'),'core');
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
}

if (empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	if ($config['use_captcha']) {
		require_once 'includes/classes/sco_captcha.class.php';
		$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
		$_SESSION['captcha_word']=$c->gen_rnd_string(4);
		$output['rand']=make_seed();
		$output['use_captcha']=true;
	}
}

$output['lang_32']=sanitize_and_format($GLOBALS['_lang'][32],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_33']=sanitize_and_format($GLOBALS['_lang'][33],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_34']=sanitize_and_format($GLOBALS['_lang'][34],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_259']=sanitize_and_format($GLOBALS['_lang'][259],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','contact.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']=$GLOBALS['_lang'][206];
$tplvars['page_title']=$GLOBALS['_lang'][206];
$tplvars['page']='contact';
$tplvars['css']='contact.css';
include 'frame.php';

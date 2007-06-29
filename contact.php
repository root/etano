<?php
/******************************************************************************
Etano
===============================================================================
File:                       contact.php
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
check_login_member('contact');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$config=get_site_option(array('use_captcha'),'core');
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
}

if (!isset($_SESSION['user']['user_id'])) {
	if ($config['use_captcha']) {
		require_once 'includes/classes/sco_captcha.class.php';
		$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
		$_SESSION['captcha_word']=$c->gen_rnd_string(4);
		$output['rand']=make_seed();
		$output['use_captcha']=true;
	}
}

$tpl->set_file('content','contact.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Contact Us';
$tplvars['page_title']='Contact Us';
$tplvars['page']='contact';
$tplvars['css']='contact.css';
include 'frame.php';
?>
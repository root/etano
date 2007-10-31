<?php
/******************************************************************************
Etano
===============================================================================
File:                       pass_lost.php
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

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	unset($_SESSION['topass']['input']);
}
if (get_site_option('use_captcha','core')) {
	$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
	$_SESSION['captcha_word']=$c->gen_rnd_string(4);
	$output['rand']=make_seed();
	$output['use_captcha']=true;
}
$tpl->set_file('content','pass_lost.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Lost Password';
$tplvars['page_title']='Lost Password';
$tplvars['page']='pass_lost';
$tplvars['css']='pass_lost.css';
$no_timeout=true;
include 'frame.php';

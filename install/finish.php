<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/finish.php
$Revision: 213 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/defines.inc.php';
require_once '../includes/sessions.inc.php';
require_once '../includes/sco_functions.inc.php';
require_once '../includes/classes/phemplate.class.php';

$output=array();
$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','finish.html');

$output['notify']='http://www.datemill.com/remote/install_notify.php?lk='.md5(_LICENSE_KEY_).'&v='._INTERNAL_VERSION_.'&bu='.rawurlencode(base64_encode(_BASEURL_));
if (!empty($_SESSION['install']['phpbin'])) {
	$output['phpbin']=$_SESSION['install']['phpbin'];
} else {
	$output['nophpbin']=true;
	$output['phpbin']='/path/to/php';
}

if (!empty($_GET['mt']) && $_GET['mt']==MESSAGE_INFO) {
	$output['success']=true;
}

$output['basepath']=_BASEPATH_;
$output['baseurl']=_BASEURL_;

$tplvars=array();
$tplvars['page_title']='Etano Install Process';
$tplvars['css']='finish.css';
$tplvars['page']='finish';
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);
include 'frame.php';

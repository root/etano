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
require_once '../includes/classes/fileop.class.php';

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

$output['basepath']=_BASEPATH_;
$output['baseurl']=_BASEURL_;

$tplvars=array();
$tplvars['page_title']='Etano Install Process';
$tplvars['css']='finish.css';
$tplvars['page']='finish';
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);
$tpl->set_file('frame','frame.html');
$message=isset($message) ? $message : (isset($topass['message']) ? $topass['message'] : (isset($_SESSION['topass']['message']) ? $_SESSION['topass']['message'] : array()));
if (!empty($message)) {
	$message['type']=(!isset($message['type']) || $message['type']==MESSAGE_ERROR) ? 'message_error' : 'message_info';
	if (is_array($message['text'])) {
		$message['text']=join('<br>',$message['text']);
	}
	$tpl->set_var('message',$message);
}
$tpl->set_var('tplvars',$tplvars);

$fileop=new fileop();
$fileop->delete(_BASEPATH_.'/install');

echo $tpl->process('frame','frame',TPL_FINISH | TPL_OPTIONAL | TPL_INCLUDE);
if (isset($_SESSION['topass'])) {
	unset($_SESSION['topass']);
}

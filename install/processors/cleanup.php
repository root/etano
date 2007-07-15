<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/processors/cleanup.php
$Revision: 192 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/defines.inc.php';
require_once '../../includes/sco_functions.inc.php';
set_time_limit(0);

$error=false;
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	require_once '../../includes/classes/fileop.class.php';
	$fileop=new fileop();
	$mt='';
	if ($fileop->delete(dirname(__FILE__).'/../../install2/')) {
		$mt=MESSAGE_INFO;
	} else {
		$mt=MESSAGE_ERROR;
	}
}
//$nextpage='http://www.datemill.com/remote/finish.php?lk='.md5(_LICENSE_KEY_).'&v='._INTERNAL_VERSION_.'&mt='.$mt.'&bu='.rawurlencode(base64_encode(_BASEURL_)).'&bp='.rawurlencode(base64_encode(_BASEPATH_));
$nextpage='http://dating.sco.ro/datemill/remote/finish.php?lk='.md5(_LICENSE_KEY_).'&v='._INTERNAL_VERSION_.'&mt='.$mt.'&bu='.rawurlencode(base64_encode(_BASEURL_)).'&bp='.rawurlencode(base64_encode(_BASEPATH_));
if (!empty($_SESSION['install']['phpbin'])) {
	$nextpage.='&p='.rawurlencode(base64_encode($_SESSION['install']['phpbin']));
}
redirect2page($nextpage,$topass,'',true);

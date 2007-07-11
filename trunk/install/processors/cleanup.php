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
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/sco_functions.inc.php';
set_time_limit(0);

$error=false;
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	define('_BASEPATH_',$_SESSION['install']['input']['basepath']);
	define('_FILEOP_MODE_',$_SESSION['install']['write']==2 ? 'ftp' : 'disk');
	define('_FTPHOST_',$_SESSION['install']['input']['ftphost']);
	define('_FTPUSER_',$_SESSION['install']['input']['ftpuser']);
	define('_FTPPASS_',$_SESSION['install']['input']['ftppass']);
	define('_FTPPATH_',$_SESSION['install']['input']['ftppath']);

	require_once '../../includes/classes/fileop.class.php';
	$fileop=new fileop();
	$mt='';
	if ($fileop->delete(dirname(__FILE__).'/../../install2/')) {
		$mt=MESSAGE_INFO;
	} else {
		$mt=MESSAGE_ERROR;
	}
}
$nextpage='http://www.datemill.com/rpc/finish.php?lk='.md5(_LICENSE_KEY_).'&v='._INTERNAL_VERSION_.'&mt='.$mt.'&bu='.base64_encode(_BASEURL_);
if (!empty($_SESSION['install']['phpbin'])) {
	$nextpage.='&p='.base64_encode($_SESSION['install']['phpbin']);
}
redirect2page($nextpage,$topass,'',true);
?>
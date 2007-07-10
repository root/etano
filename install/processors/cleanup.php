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
$nextpage='info.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	define('_FILEOP_MODE_',$_SESSION['install']['write']==2 ? 'ftp' : 'disk');

	require_once '../../includes/classes/fileop.class.php';
	$fileop=new fileop();
	$fileop->delete(dirname(__FILE__).'/../../install2/');

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text'][]='The install folder has been removed succesfully. Please proceed to <a class="content-link simple" href="admin/index.php">admin panel</a> to configure your site.';
}
$my_url=str_replace('/install/processors/cleanup.php','',$_SERVER['PHP_SELF']);
define('_BASEURL_',((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$my_url);
redirect2page($nextpage,$topass,$qs);
?>
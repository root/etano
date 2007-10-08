<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/processors/cleanup.php
$Revision$
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
	if ($fileop->delete(_BASEPATH_.'/install')) {
		$mt=MESSAGE_INFO;
	} else {
		$mt=MESSAGE_ERROR;
	}
}
redirect2page('finish.php',array(),'success='.$mt);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/index.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';

if (!isset($_SESSION['admin']['admin_id'])) {
	if (isset($_SESSION['topass']) && !empty($_SESSION['topass'])) {
		$topass=$_SESSION['topass'];
		$_SESSION['topass']=array();
	}
	$message=isset($topass['message']) ? $topass['message'] : '';

	$tpl=new phemplate('skin/','remove_nonjs');
	$tpl->set_file('frame','index.html');
	$tpl->set_var('title','Admin panel login');
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('message',$message);
	echo $tpl->process('','frame',TPL_FINISH);
} else {
	redirect2page('admin/cpanel.php');
}
?>
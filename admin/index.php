<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/index.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';

if (!isset($_SESSION[_LICENSE_KEY_]['admin']['admin_id'])) {
	if (!empty($_SESSION['topass'])) {
		$topass=$_SESSION['topass'];
		$_SESSION['topass']=array();
	}
	$message=isset($topass['message']) ? $topass['message'] : '';

	$tpl=new phemplate('skin/','remove_nonjs');
	$tpl->set_file('frame','index.html');
	$tpl->set_var('title','Admin panel login');
	$tpl->set_var('baseurl',_BASEURL_);
	$tpl->set_var('message',$message);
	$tpl->set_var('tplvars',$tplvars);
	echo $tpl->process('','frame',TPL_FINISH);
} else {
	redirect2page('admin/cpanel.php');
}

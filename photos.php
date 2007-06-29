<?php
/******************************************************************************
Etano
===============================================================================
File:                       photos.php
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
check_login_member('all');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','photos.html');
$tpl->process('content','content');

$tplvars['title']='Browse Photos';
$tplvars['page_title']='Browse Photos';
$tplvars['page']='photos';
$tplvars['css']='photos.css';
if (is_file('photos_left.php')) {
	include 'photos_left.php';
}
include 'frame.php';
?>
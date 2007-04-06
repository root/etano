<?php
/******************************************************************************
newdsb
===============================================================================
File:                       photos_upload.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(8);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','photos_upload.html');
$tpl->process('content','content');

$tplvars['title']='Upload photos';
$tplvars['page_title']='Upload photos';
$tplvars['page']='photos_upload';
$tplvars['css']='photos_upload.css';
if (is_file('photos_upload_left.php')) {
	include 'photos_upload_left.php';
}
include 'frame.php';
?>
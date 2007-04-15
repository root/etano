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

$output['max_file_size']=get_site_option('max_size','core_photo');
if (empty($output['max_file_size'])) {
	$output['max_file_size']=ini_get('upload_max_filesize');
	if (strcasecmp(substr($output['max_file_size'],-1),'m')==0) {
		$output['max_file_size']=((int)substr($output['max_file_size'],0,-1))*1024*1024;
	} elseif (strcasecmp(substr($output['max_file_size'],-1),'k')==0) {
		$output['max_file_size']=((int)substr($output['max_file_size'],0,-1))*1024;
	}
}

$tpl->set_file('content','photos_upload.html');
$tpl->set_var('output',$output);
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
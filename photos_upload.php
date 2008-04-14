<?php
/******************************************************************************
Etano
===============================================================================
File:                       photos_upload.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/photos.inc.php';
check_login_member('upload_photos');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$output['max_file_size']=get_site_option('max_size','core_photo');
if (empty($output['max_file_size'])) {
	$output['max_file_size']=ini_get('upload_max_filesize');
	if (strcasecmp(substr($output['max_file_size'],-1),'m')==0) {
		$output['max_file_size']=((int)substr($output['max_file_size'],0,-1))*1024*1024;
	} elseif (strcasecmp(substr($output['max_file_size'],-1),'k')==0) {
		$output['max_file_size']=((int)substr($output['max_file_size'],0,-1))*1024;
	}
}
$output['photos_remaining']=get_user_settings($_SESSION[_LICENSE_KEY_]['user']['user_id'],'core_photo','max_user_photos');
if ($output['photos_remaining']==-1) {
	$output['photos_remaining']=$GLOBALS['_lang'][149];
} else {
	$output['photos_remaining']=sprintf($GLOBALS['_lang'][150],$output['photos_remaining']);
}
$output['lang_257']=sanitize_and_format($GLOBALS['_lang'][257],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$tpl->set_file('content','photos_upload.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][151];
$tplvars['page_title']=$GLOBALS['_lang'][151];
$tplvars['page']='photos_upload';
$tplvars['css']='photos_upload.css';
if (is_file('photos_upload_left.php')) {
	include 'photos_upload_left.php';
}
include 'frame.php';

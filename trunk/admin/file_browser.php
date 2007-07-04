<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/file_browser.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$path=str_replace('..','',preg_replace('~[^a-zA-Z0-9\._/-]~','',sanitize_and_format_gpc($_GET,'path',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'')));
if (!empty($path)) {
	$path=preg_replace("/^(\.|\/)+/",'',$path);
}

$tpl->set_file('content','file_browser.html');
$tpl->set_var('path',$path);
$tpl->process('content','content');

$tplvars['title']='Browse your files';
$tplvars['css']='file_browser.css';
$tplvars['page']='file_browser';
include 'frame.php';
?>
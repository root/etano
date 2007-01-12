<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/file_browser.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once 'includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$path=str_replace('..','',preg_replace('~[^a-zA-Z0-9\._/-]~','',sanitize_and_format_gpc($_GET,'path',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'')));
if (!empty($path)) {
	$path=preg_replace("/^(\.|\/)+/",'',$path);
}

$tpl->set_file('content','file_browser.html');
$tpl->set_var('path',$path);
$tpl->process('content','content');

$tplvars['title']='Browse your files';
include 'frame.php';
?>
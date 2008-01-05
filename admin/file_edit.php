<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/file_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
$output['file']=str_replace('..','',preg_replace('~[^a-zA-Z0-9\._/-]~','',sanitize_and_format_gpc($_GET,'f',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'')));
if (!empty($output['file']) && $output['file']{0}=='/') {
	$output['file']=substr($output['file'],1);
}
$file=_BASEPATH_.'/'.$output['file'];

$mode=isset($_GET['m']) ? (int)$_GET['m'] : 1;

if (is_file($file)) {
	$output['file_content']=str_replace(array('{','}'),array('&#x007B;','&#x007D;'),sanitize_and_format(file_get_contents($file),TYPE_STRING,$__field2format[TEXT_DB2EDIT]));
}

$tpl->set_file('content','file_edit.html');
$output['path']=urlencode(pathinfo($output['file'],PATHINFO_DIRNAME));
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='File editor';
$tplvars['css']='file_edit.css';
$tplvars['page']='file_edit';
include 'frame.php';

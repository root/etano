<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/newsletter.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} else {
	$output['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return2']=rawurldecode($output['return']);
}

$tpl->set_file('content','newsletter.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Send Newsletter';
$tplvars['page']='newsletter';
include 'frame.php';

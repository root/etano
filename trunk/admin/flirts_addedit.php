<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/flirts_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/flirts.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$flirts_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['flirt_id'])) {
	$flirt_id=(int)$_GET['flirt_id'];
	$query="SELECT `flirt_id`,`flirt_text`,`flirt_type` FROM `{$dbtable_prefix}flirts` WHERE `flirt_id`=$flirt_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['flirt_text']=sanitize_and_format($output['flirt_text'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$output['flirt_type']=vector2radios($flirt_types,'flirt_type',$output['flirt_type']);

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}
$tpl->set_file('content','flirts_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Flirt Management';
$tplvars['css']='flirts_addedit.css';
$tplvars['page']='flirts_addedit';
include 'frame.php';

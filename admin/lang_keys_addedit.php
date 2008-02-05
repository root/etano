<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/lang_keys_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$accepted_lk_types=array(FIELD_TEXTFIELD=>'Textfield',FIELD_TEXTAREA=>'Textarea');
$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
$output['lk_type']=FIELD_TEXTFIELD;
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['lk_id'])) {
	$lk_id=(int)$_GET['lk_id'];
	$query="SELECT `lk_id`,`alt_id_text`,`lk_type`,`lk_diz`,`lk_use`,`save_file` FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id`=$lk_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['lk_diz']=sanitize_and_format($output['lk_diz'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$output['alt_id_text']=sanitize_and_format($output['alt_id_text'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$output['lk_type']=vector2options($accepted_lk_types,$output['lk_type']);

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$tpl->set_file('content','lang_keys_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Add/Edit Language Keys';
$tplvars['page']='lang_keys_addedit';
$tplvars['css']='lang_keys_addedit.css';
include 'frame.php';

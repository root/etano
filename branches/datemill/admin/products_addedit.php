<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/products_addedit.php
$Revision: 217 $
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

$dbtable_prefix='';

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
$output['fk_dev_id']=0;
$output['is_visible']=1;
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['prod_id'])) {
	$prod_id=(int)$_GET['prod_id'];
	$query="SELECT `prod_id`,`is_visible`,`prod_type`,`module_code`,`prod_name`,`prod_diz`,`prod_pic`,`fk_dev_id`,`version`,`last_changed`,`price`,`filename` FROM `{$dbtable_prefix}products` WHERE `prod_id`=$prod_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['prod_name']=sanitize_and_format($output['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$output['prod_diz']=sanitize_and_format($output['prod_diz'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$output['prod_type']=$accepted_module_types[$output['prod_type']];
	}
}

$output['is_visible']=!empty($output['is_visible']) ? 'checked="checked"' : '';
$output['fk_dev_id']=dbtable2options('developers','dev_id','dev_name','dev_name',$output['fk_dev_id']);
if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$tpl->set_file('content','products_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Product Management';
$tplvars['css']='products_addedit.css';
$tplvars['page']='products_addedit';
include 'frame.php';

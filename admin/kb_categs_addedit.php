<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/kb_categs_addedit.php
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
require_once '../includes/tables/kb_categs.inc.php';
allow_dept(DEPT_ADMIN);

$dbtable_prefix='';

$tpl=new phemplate('skin/','remove_nonjs');

$output=$kb_categs_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['kbc_id'])) {
	$kbc_id=(int)$_GET['kbc_id'];
	$query="SELECT `kbc_id`,`fk_kbc_id_parent`,`kbc_title` FROM `{$dbtable_prefix}kb_categs` WHERE `kbc_id`=$kbc_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['kbc_title']=sanitize_and_format($output['kbc_title'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
	// because of the GET, our 'return' is decoded
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$output['fk_kbc_id_parent']=dbtable2options("`{$dbtable_prefix}kb_categs`","`kbc_id`","`kbc_title`","`kbc_title`",$output['fk_kbc_id_parent'],"`kbc_id`<>".$output['kbc_id']);

$tpl->set_file('content','kb_categs_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Knowledge Base Categories Management';
$tplvars['page']='kb_categs_addedit';
$tplvars['css']='kb_categs_addedit.css';
include 'frame.php';

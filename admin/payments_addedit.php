<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/payments_addedit.php
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

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['payment_id'])) {
	$payment_id=(int)$_GET['payment_id'];
	$query="SELECT `payment_id`,`gateway`,`gw_txn`,`name`,`country`,`email`,`amount_paid`,`refunded`,DATE_FORMAT(`date`,'%Y-%m-%d') as `date` FROM `{$dbtable_prefix}payments` WHERE `payment_id`=$payment_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['name']=sanitize_and_format($output['name'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$output['country']=sanitize_and_format($output['country'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}
if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

if (isset($_GET['silent'])) {
	$output['silent']=1;
}
$tpl->set_file('content','payments_addedit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
print $tpl->process('content','content',TPL_FINISH);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_products_addedit.php
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
require_once '../includes/tables/user_products.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$user_products_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['uprod_id'])) {
	$uprod_id=(int)$_GET['uprod_id'];
	$query="SELECT `uprod_id`,`fk_prod_id`,`fk_site_id`,`fk_user_id`,`fk_payment_id`,`license` FROM `user_products` WHERE `uprod_id`=$uprod_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
} elseif (!empty($_GET['sid'])) {
	$output['fk_site_id']=(int)$_GET['sid'];
	$query="SELECT `fk_user_id` FROM `user_sites` WHERE `site_id`=".$output['fk_site_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$output['fk_user_id']=mysql_result($res,0,0);
} else {
	redirect2page('admin/cpanel.php');
}

$query="SELECT `gateway`,`gw_txn`,`amount_paid`,UNIX_TIMESTAMP(`date`) as `date` FROM `{$dbtable_prefix}payments` WHERE `payment_id`=".$output['fk_payment_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=array_merge($output,mysql_fetch_assoc($res));
	$output['date']=date('Y-m-d',$output['date']);
}

$output['fk_prod_id']=dbtable2options('`products`','`prod_id`','`prod_name`','`prod_name`',$output['fk_prod_id']);
$output['fk_site_id']=dbtable2options('`user_sites`','`site_id`',"CONCAT(`site_id`,' - ',`baseurl`)",'`site_id`',$output['fk_site_id'],'`fk_user_id`='.$output['fk_user_id']);

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}


$tpl->set_file('content','user_products_addedit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
print $tpl->process('content','content',TPL_FINISH);

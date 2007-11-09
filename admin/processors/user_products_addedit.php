<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/user_products_addedit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/user_products.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/user_products.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($user_products_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_products_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format($_POST['return'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE);
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['fk_prod_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the product';
		$input['error_fk_prod_id']='red_border';
	}
	if (empty($input['fk_site_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the site';
		$input['error_fk_site_id']='red_border';
	}

	if (empty($input['license'])) {
		$input['license_md5']='';
	} else {
		$input['license_md5']=md5($input['license']);
	}

	$input['gateway']=sanitize_and_format_gpc($_POST,'gateway',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['gw_txn']=sanitize_and_format_gpc($_POST,'gw_txn',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['amount_paid']=isset($_POST['amount_paid']) ? (float)$_POST['amount_paid'] : 0;

	if (!$error) {
		if (!empty($input['fk_payment_id'])) {
			$query="UPDATE `{$dbtable_prefix}payments` SET `gateway`='".$input['gateway']."',`gw_txn`='".$input['gw_txn']."',`amount_paid`='".$input['amount_paid']."' WHERE `payment_id`=".$input['fk_payment_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		} else {
			$query="INSERT INTO `{$dbtable_prefix}payments` SET `gateway`='".$input['gateway']."',`gw_txn`='".$input['gw_txn']."',`amount_paid`='".$input['amount_paid']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_payment_id']=mysql_insert_id();
		}
		if (!empty($input['uprod_id'])) {
			$query="UPDATE `user_products` SET ";
			foreach ($user_products_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `uprod_id`=".$input['uprod_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Product changed.';
		} else {
			unset($input['uprod_id']);
			$query="INSERT INTO `user_products` SET ";
			foreach ($user_products_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Product added for the specified user/site.';
		}
	} else {
		$nextpage='user_products_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=isset($input['return']) ? rawurlencode($input['return']) : '';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/payments.php
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
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$payment_id=isset($_GET['payment_id']) ? (int)$_GET['payment_id'] : 0;
$act=isset($_GET['act']) ? $_GET['act'] : '';

if (!empty($payment_id)) {
	if ($act=='s') {	// mark as fraud
		$suspect_reason=sanitize_and_format_gpc($_GET,'suspect_reason',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		$query="UPDATE `{$dbtable_prefix}payments` SET `is_suspect`=1,`suspect_reason`='$suspect_reason' WHERE `payment_id`=$payment_id";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Payment marked as fraud.';
	} elseif ($act=='a') {	// not fraud
		$query="UPDATE `{$dbtable_prefix}payments` SET `is_suspect`=0,`suspect_reason`='' WHERE `payment_id`=$payment_id";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Payment marked as not a fraud.';
	}
}

if (!isset($_GET['silent'])) {
	if (!empty($return)) {
		$nextpage=_BASEURL_.'/admin/'.$return;
	} else {
		$nextpage=_BASEURL_.'/admin/payment_history.php';
	}
	redirect2page($nextpage,$topass,'',true);
} else {
	$_SESSION['topass']=$topass;
	echo 1;
}

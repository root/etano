<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/payments_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$payments_default['defaults']=array('payment_id'=>0,'name'=>'','country'=>'','email'=>'','amount_paid'=>0,'refunded'=>0,'gateway'=>'','gw_txn'=>'','date'=>'');
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['payment_id']=isset($_POST['payment_id']) ? (int)$_POST['payment_id'] : 0;
	$input['name']=sanitize_and_format_gpc($_POST,'name',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['country']=sanitize_and_format_gpc($_POST,'country',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['email']=sanitize_and_format_gpc($_POST,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['amount_paid']=sanitize_and_format_gpc($_POST,'amount_paid',TYPE_FLOAT,0,0);
	$input['refunded']=sanitize_and_format_gpc($_POST,'refunded',TYPE_FLOAT,0,0);
	$input['gateway']=sanitize_and_format_gpc($_POST,'gateway',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['gw_txn']=sanitize_and_format_gpc($_POST,'gw_txn',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['date']=sanitize_and_format_gpc($_POST,'date',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		if (!empty($input['payment_id'])) {
			$query="UPDATE `{$dbtable_prefix}payments` SET ";
			foreach ($payments_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `payment_id`=".$input['payment_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Payment changed.';
		} else {
			unset($input['payment_id']);
			$query="INSERT INTO `{$dbtable_prefix}payments` SET ";
			foreach ($payments_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['payment_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Payment added.';
		}
	} else {
		$nextpage='payments_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=isset($input['return']) ? rawurlencode($input['return']) : '';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (!isset($_POST['silent'])) {
	$nextpage=_BASEURL_.'/admin/'.$nextpage;
	redirect2page($nextpage,$topass,'',true);
} else {
	$_SESSION['topass']=$topass;
	?>
	<script type="text/javascript">
		// thickbox
		parent.location=parent.location;
	</script>
	<?php
}

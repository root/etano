<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/subscriptions.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
check_login_member('auth');

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='profile.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['subscr_id']=isset($_POST['subscr_id']) ? (int)$_POST['subscr_id'] : 0;
	$input['module_code']=sanitize_and_format_gpc($_POST,'module_code',TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);

	if (empty($input['subscr_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the desired membership type.';	//translate this
	}
	if (empty($input['module_code'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the desired payment system.';	//translate this
	}

	if (!$error) {
		$query="SELECT * FROM `{$dbtable_prefix}site_options3` WHERE `config_option`='module_active' AND `config_value`=1 AND `fk_module_code`='".$input['module_code']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$module=array();
		if (mysql_num_rows($res)) {
			$module=mysql_fetch_assoc($res);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid membership type. Please select another.';	//translate this
		}
		$query="SELECT * FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`='".$input['subscr_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$subscription=array();
		if (mysql_num_rows($res)) {
			$subscription=mysql_fetch_assoc($res);
			$subscription['user_id']=$_SESSION['user']['user_id'];
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid membership type. Please select another.';	//translate this
		}

		if (!$error) {
			if (is_file(_BASEPATH_.'/plugins/payment/'.$input['module_code'].'/'.$input['module_code'].'.class.php')) {
				include_once _BASEPATH_.'/plugins/payment/'.$input['module_code'].'/'.$input['module_code'].'.class.php';
				$class='payment_'.$input['module_code'];

				$pay=new $class;
				$pay->redirect2gateway($subscription);
			}
		}
	}
}
redirect2page('home.php',$topass,$qs);

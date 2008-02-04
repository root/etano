<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_payment_history.php
$Revision$
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

$loop=array();
$output=array();
if (!empty($_GET['uid'])) {
	$output['uid']=(int)$_GET['uid'];

	$query="SELECT `m_value`,`m_name` FROM `{$dbtable_prefix}memberships`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$memberships=array();
	while ($rsrow=mysql_fetch_row($res)) {
		$memberships[$rsrow[0]]=$rsrow[1];
	}

	$config=get_site_option(array('date_format','time_offset'),'def_user_prefs');

	$query="SELECT `payment_id`,`fk_user_id`,`_user`,`gateway`,`gw_txn`,`name`,`country`,`email`,`is_subscr`,`m_value_to`,`amount_paid`,`refunded`,UNIX_TIMESTAMP(`paid_from`) as `paid_from`,UNIX_TIMESTAMP(`paid_until`) as `paid_until`,UNIX_TIMESTAMP(`date`) as `date`,`is_suspect`,`suspect_reason` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`=".$output['uid'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$output['total']=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (!empty($rsrow['is_subscr'])) {
			$rsrow['m_value_to']=isset($memberships[$rsrow['m_value_to']]) ? $memberships[$rsrow['m_value_to']] : '?';
			$rsrow['paid_from']=strftime($config['date_format'],$rsrow['paid_from']+$config['time_offset']);
			$rsrow['paid_until']=strftime($config['date_format'],$rsrow['paid_until']+$config['time_offset']);
		} else {
			$rsrow['paid_from']=strftime($config['date_format'],$rsrow['date']);
			$rsrow['m_value_to']='Product';
			unset($rsrow['paid_until']);
		}
		$output['user']=$rsrow['_user'];
		if (empty($rsrow['is_suspect'])) {
			$output['total']+=((float)$rsrow['amount_paid']-(float)$rsrow['refunded']);
		}
		if ($rsrow['refunded']!=0) {
			$rsrow['refunded']='(<span class="alert">-$'.$rsrow['refunded'].'</span>)';
		} else {
			unset($rsrow['refunded']);
		}
		if (!empty($rsrow['is_suspect'])) {
			$rsrow['suspect_reason']=sanitize_and_format($rsrow['suspect_reason'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		} else {
			unset($rsrow['is_suspect']);
		}
		$loop[]=$rsrow;
	}
	$output['total']=number_format($output['total'],2);
}
//$loop=sanitize_and_format($loop,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='user_payment_history.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','user_payment_history.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$message=isset($message) ? $message : (isset($topass['message']) ? $topass['message'] : (isset($_SESSION['topass']['message']) ? $_SESSION['topass']['message'] : array()));
if (!empty($message)) {
	$tpl->set_var('message',$message['text']);
	$tpl->set_var('message_class',($message['type']==MESSAGE_ERROR) ? 'message_error_small' : (($message['type']==MESSAGE_INFO) ? 'message_info_small' : 'message_info_small'));
}
echo $tpl->process('','content',TPL_FINISH | TPL_OPTIONAL | TPL_LOOP | TPL_OPTLOOP);
unset($_SESSION['topass']);

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

	$config=get_site_option(array('date_format'),'core');

	$query="SELECT *,UNIX_TIMESTAMP(`paid_from`) as `paid_from`,UNIX_TIMESTAMP(`paid_until`) as `paid_until` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`='".$output['uid']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$output['total']=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['m_value_from']=$memberships[$rsrow['m_value_from']];
		$rsrow['m_value_to']=$memberships[$rsrow['m_value_to']];
		$rsrow['paid_from']=strftime($config['date_format'],$rsrow['paid_from']);
		$rsrow['paid_until']=strftime($config['date_format'],$rsrow['paid_until']);
		$output['user']=$rsrow['_user'];
		$output['total']+=((float)$rsrow['amount_paid']);
		$loop[]=$rsrow;
	}
}
$loop=sanitize_and_format($loop,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['total']=number_format($output['total'],2);

$tpl->set_file('content','user_payment_history.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$message=isset($message) ? $message : (isset($topass['message']) ? $topass['message'] : (isset($_SESSION['topass']['message']) ? $_SESSION['topass']['message'] : array()));
if (!empty($message)) {
	$tpl->set_var('message',$message['text']);
	$tpl->set_var('message_class',($message['type']==MESSAGE_ERROR) ? 'message_error_small' : (($message['type']==MESSAGE_INFO) ? 'message_info_small' : 'message_info_small'));
}
echo $tpl->process('','content',TPL_FINISH | TPL_LOOP);
unset($_SESSION['topass']);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/payment_history.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (!empty($_GET['date_start'])) {
	$output['date_start']=date('Y-m-d',strtotime($_GET['date_start']));
} else {
	$output['date_start']=date('Y-m-01');
}
if (!empty($_GET['date_end'])) {
	$output['date_end']=$_GET['date_end'];
} else {
	$output['date_end']=date('Y-m-t');
}

$query="SELECT `m_value`,`m_name` FROM `{$dbtable_prefix}memberships`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$memberships=array();
while ($rsrow=mysql_fetch_row($res)) {
	$memberships[$rsrow[0]]=$rsrow[1];
}

$config=get_site_option(array('date_format'),'core');

$query="SELECT `fk_user_id`,`_user`,`gateway`,`gw_txn`,`name`,`country`,`email`,`m_value_from`,`m_value_to`,`amount_paid`,`refunded`,UNIX_TIMESTAMP(`paid_from`) as `paid_from`,UNIX_TIMESTAMP(`paid_until`) as `paid_until` FROM `{$dbtable_prefix}payments` WHERE `date`>='".$output['date_start']."' AND `date`<='".$output['date_end']."' ORDER BY `payment_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['total']=0;
$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['m_value_from']=$memberships[$rsrow['m_value_from']];
	$rsrow['m_value_to']=$memberships[$rsrow['m_value_to']];
	$rsrow['paid_from']=strftime($config['date_format'],$rsrow['paid_from']);
	$rsrow['paid_until']=strftime($config['date_format'],$rsrow['paid_until']);
	$output['total']+=(float)$rsrow['amount_paid']-(float)$rsrow['refunded'];
	if ($rsrow['refunded']!=0) {
		$rsrow['refunded']='(<span class="alert">$'.$rsrow['refunded'].'</span>)';
	} else {
		unset($rsrow['refunded']);
	}
	$loop[]=$rsrow;
}
//$loop=sanitize_and_format($loop,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['total']=number_format($output['total'],2);

$tpl->set_file('content','payment_history.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP);

$tplvars['title']='Payment History';
$tplvars['page']='payment_history';
$tplvars['css']='payment_history.css';
include 'frame.php';
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       subscriptions.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/payment.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$query="SELECT a.`module_code`,a.`module_name`,a.`module_diz` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_PAYMENT." AND b.`fk_module_code`=a.`module_code` AND b.`config_option`='enabled' AND `config_value`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$active_gateways=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	if (is_file(_BASEPATH_.'/plugins/payment/'.$rsrow['module_code'].'/'.$rsrow['module_code'].'.class.php')) {
		include_once _BASEPATH_.'/plugins/payment/'.$rsrow['module_code'].'/'.$rsrow['module_code'].'.class.php';
		$temp='payment_'.$rsrow['module_code'];
		$rsrow['call']=new $temp();
		$rsrow['module_name']=sanitize_and_format($rsrow['module_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['module_diz']=sanitize_and_format($rsrow['module_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$active_gateways[]=$rsrow;
	}
}

$query="SELECT `subscr_id`,`subscr_name`,`subscr_diz`,`price`,`currency`,`is_recurent`,`duration` FROM `{$dbtable_prefix}subscriptions` WHERE `is_visible`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$loop=array();
$payment=array();
$payment['user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
$payment['dm_item_type']='subscr';
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['subscr_name']=sanitize_and_format($rsrow['subscr_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['subscr_diz']=sanitize_and_format($rsrow['subscr_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	if ((int)$rsrow['price']==(float)$rsrow['price']) {
		$rsrow['price']=number_format($rsrow['price'],0);
	} else {
		$rsrow['price']=number_format($rsrow['price'],2);
	}
	$payment['internal_name']=$rsrow['subscr_name'];
	$payment['internal_id']=$rsrow['subscr_id'];
	$payment['price']=$rsrow['price'];
	$payment['is_recurent']=$rsrow['is_recurent'];
	$payment['duration']=$rsrow['duration'];
	$payment['currency']=$rsrow['currency'];
	for ($i=0;isset($active_gateways[$i]);++$i) {
		$rsrow['payment_links'][]=$active_gateways[$i]['call']->get_buy_button($payment);
	}
	$rsrow['payment_links']=join('</li><li>',$rsrow['payment_links']);
	$loop[]=$rsrow;
}

$tpl->set_file('content','subscriptions.html');
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP);

$tplvars['title']='Upgrade your membership';
$tplvars['page_title']='Upgrade your membership';
$tplvars['page']='subscriptions';
$tplvars['css']='subscriptions.css';
if (is_file('subscriptions_left.php')) {
	include 'subscriptions_left.php';
}
include 'frame.php';

<?php
/******************************************************************************
Etano
===============================================================================
File:                       purchase.php
$Revision: 221 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
//check_login_member('purchase');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
}
include_once _BASEPATH_.'/plugins/payment/paypal/paypal.class.php';
include_once _BASEPATH_.'/plugins/payment/twocheckout/twocheckout.class.php';
$paypal=new payment_paypal();
$twocheckout=new payment_twocheckout();

$payment['dm_item_type']='prod';
if (isset($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	$payment['user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
}
$payment['currency']='USD';
$payment['dm_item_type']='prod';

$query="SELECT a.`prod_id`,a.`prod_name`,a.`prod_diz`,a.`prod_pic`,b.`dev_name`,a.`version`,a.`price` FROM `products` a,`developers` b WHERE a.`fk_dev_id`=b.`dev_id` AND a.`is_visible`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['prod_name']=sanitize_and_format($rsrow['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['prod_diz']=sanitize_and_format($rsrow['prod_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['dev_name']=sanitize_and_format($rsrow['dev_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	if ((int)$rsrow['price']==(float)$rsrow['price']) {
		$rsrow['price']=number_format($rsrow['price'],0);
	} else {
		$rsrow['price']=number_format($rsrow['price'],2);
	}
	$payment['internal_name']=$rsrow['prod_name'];
	$payment['internal_id']=$rsrow['prod_id'];
	$payment['price']=$rsrow['price'];
	$rsrow['paypal_button']=$paypal->get_buy_button($payment);
	$rsrow['2co_button']=$twocheckout->get_buy_button($payment);
	$loop[]=$rsrow;
}
$tpl->set_file('content','purchase.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_OPTIONAL | TPL_LOOP);

$tplvars['title']='Purchase';
$tplvars['page_title']='Purchase Etano';
$tplvars['page']='purchase';
$tplvars['menu_buy']='active';
$tplvars['css']='purchase.css';
include 'frame.php';

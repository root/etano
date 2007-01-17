<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/payment/twocheckout/twocheckout.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/ipayment.class.php';

class payment_twocheckout extends ipayment {
	var $module_code='twocheckout';
	var $from_tco=array('defaults'=>array(	'x_2checked'=>'',
											'x_MD5_Hash'=>'',
											'x_trans_id'=>'',
											'x_amount'=>'',
											'card_holder_name'=>'',
											'x_Country'=>'',
											'x_City'=>'',
											'x_State'=>'',
											'x_Zip'=>'',
											'x_Address'=>'',
											'x_Email'=>'',
											'x_Phone'=>'',
											'demo'=>'',
											'x_response_code'=>0,
											'x_response_reason_code'=>'',
											'x_response_reason_text'=>'',
											'subscr_id'=>0,
											'user_id'=>0),
						'types'=>	array(	'x_2checked'=>_HTML_TEXTFIELD_,
											'x_MD5_Hash'=>_HTML_TEXTFIELD_,
											'x_trans_id'=>_HTML_TEXTFIELD_,
											'x_amount'=>_HTML_FLOAT_,
											'card_holder_name'=>_HTML_TEXTFIELD_,
											'x_Country'=>_HTML_TEXTFIELD_,
											'x_City'=>_HTML_TEXTFIELD_,
											'x_State'=>_HTML_TEXTFIELD_,
											'x_Zip'=>_HTML_TEXTFIELD_,
											'x_Address'=>_HTML_TEXTFIELD_,
											'x_Email'=>_HTML_TEXTFIELD_,
											'x_Phone'=>_HTML_TEXTFIELD_,
											'demo'=>_HTML_TEXTFIELD_,
											'x_response_code'=>_HTML_INT_,
											'x_response_reason_code'=>_HTML_TEXTFIELD_,
											'x_response_reason_text'=>_HTML_TEXTFIELD_,
											'subscr_id'=>_HTML_INT_,
											'user_id'=>_HTML_INT_));

	function payment_twocheckout() {
		$this->ipayment();
		$this->_init();
	}

	function get_buy_button($payment=array()) {
		$this->_set_payment($payment);
		$myreturn='<form action="https://www2.2checkout.com/2co/buyer/purchase" method="post">
		<input type="hidden" name="x_login" value="'.$this->config['sid'].'" />
		<input type="hidden" name="id_type" value="1" />
		<input type="hidden" name="fixed" value="Y" />
		<input type="hidden" name="pay_method" value="CC" />
		<input type="hidden" name="x_receipt_link_url" value="'._BASEURL_.'/thankyou.php?p=twocheckout">
		<input type="hidden" name="x_invoice_num" value="1" />
		<input type="hidden" name="x_amount" value="'.$this->payment['price'].'" />
		<input type="hidden" name="c_prod" value="'.$this->payment['subscr_id'].'" />
		<input type="hidden" name="c_name" value="'.$this->payment['subscr_name'].'" />
		<input type="hidden" name="c_description" value="'.$this->payment['subscr_diz'].'" />
		<input type="hidden" name="c_tangible" value="N" />
		<input type="hidden" name="subscr_id" value="'.$this->payment['subscr_id'].'" />
		<input type="hidden" name="user_id" value="'.$this->payment['user_id'].'" />';
		if ($this->config['demo_mode']==1) {
			$myreturn.='<input type="hidden" name="demo" value="Y" />';
		}
		$myreturn.='<input name="submit" class="button" type="submit" value="Buy from 2Checkout" /></form>';
		return $myreturn;
	}


	function redirect2gateway($payment=array()) {
		$this->_set_payment($payment);
		$qs=array(	'x_login'=>$this->config['sid'],
						'fixed'=>'Y',
						'pay_method'=>'CC',
						'x_receipt_link_url'=>_BASEURL_.'/thankyou.php?p=twocheckout',
						'x_invoice_num'=>1,
						'x_amount'=>$this->payment['price'],
						'c_prod'=>$this->payment['subscr_id'],
						'id_type'=>1,
						'c_name'=>$this->payment['subscr_name'],
						'c_description'=>$this->payment['subscr_diz'],
						'c_tangible'=>'N',
						'subscr_id'=>$this->payment['subscr_id'],
						'user_id'=>$this->payment['user_id']);
		if ($this->config['demo_mode']==1) {
			$qs['demo']='Y';
		}
//		redirect2page('https://www2.2checkout.com/2co/buyer/purchase',array(),array2qs($qs),true);
		post2page('https://www2.2checkout.com/2co/buyer/purchase',$qs,true);
	}


	function thankyou(&$tpl) {
		$myreturn=false;
		$gateway_text='';
		$dbtable_prefix=$GLOBALS['dbtable_prefix'];
		$input=array();
		foreach ($this->from_tco['types'] as $k=>$v) {
			$input[$k]=sanitize_and_format_gpc($_POST,$k,$GLOBALS['__html2type'][$v],$GLOBALS['__html2format'][$v],$this->from_tco['defaults'][$k]);
		}
		if (strcasecmp($input['x_2checked'],'Y')==0) {
			if ($this->config['demo_mode']==1 && strcasecmp($input['demo'],'Y')==0) {
				$input['x_trans_id']=1;
			}
			if ($input['x_response_code']==1) {	// processed ok
				if (strcasecmp($input['x_MD5_Hash'],md5($this->config['secret'].$this->config['sid'].$input['x_trans_id'].$input['x_amount']))==0) {
					$query="SELECT `user_id` FROM `{$dbtable_prefix}user_accounts` WHERE `user_id`='".$input['user_id']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						$real_user=mysql_fetch_assoc($res);
						$query="SELECT `subscr_id`,`price`,`m_value_from`,`m_value_to`,`duration`,`duration_units` FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`='".$input['subscr_id']."' AND `is_visible`=1";
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						if (mysql_num_rows($res)) {
							$real_subscr=mysql_fetch_assoc($res);
							if (number_format($real_subscr['price'],2)==number_format($input['x_amount'],2)) {
								if (strcasecmp($input['demo'],'Y')!=0 || ($this->config['demo_mode']==1 && strcasecmp($input['demo'],'Y')==0)) {
									$input['country']=$input['x_Country'];	// needed for the fraud check
									$this->check_fraud($input);
									$query="SELECT max(`paid_until`) as `paid_until` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`='".$real_user['user_id']."'";
									if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									$paid_from=date('Ymd');
									if (mysql_num_rows($res)) {
										$paid_from=mysql_result($res,0,0);
									}
									$query="INSERT INTO `{$dbtable_prefix}payments` SET `fk_user_id`='".$real_user['user_id']."',`_user`='".$_SESSION['user']['user']."',`gateway`='twocheckout',`fk_subscr_id`='".$real_subscr['subscr_id']."',`gw_txn`='".$input['x_trans_id']."',`name`='".$input['card_holder_name']."',`country`='".$input['x_Country']."',`state`='".$input['x_State']."',`city`='".$input['x_City']."',`zip`='".$input['x_Zip']."',`street_address`='".$input['x_Address']."',`email`='".$input['x_Email']."',`phone`='".$input['x_Phone']."',`m_value_from`='".$real_subscr['m_value_from']."',`m_value_to`='".$real_subscr['m_value_to']."',`amount_paid`='".$input['x_amount']."',`is_suspect`='".(int)$this->is_fraud."',`suspect_reason`='".$this->fraud_reason."',`paid_from`='$paid_from'";
									if (!empty($real_subscr['duration'])) {
										$query.=",`paid_until`='$paid_from'+INTERVAL ".$real_subscr['duration'];
										if ($real_subscr['duration_units']=='D') {
											$query.=' DAY';
										} elseif ($real_subscr['duration_units']=='M') {
											$query.=' MONTH';
										} elseif ($real_subscr['duration_units']=='Y') {
											$query.=' YEAR';
										}
									}
									if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									if (!$this->is_fraud) {
										$query="UPDATE `{$dbtable_prefix}user_accounts` SET `membership`='".$real_subscr['m_value_to']."' WHERE `user_id`='".$real_user['user_id']."'";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										$myreturn=true;
									}
								} else {	// a demo transaction when we're not in demo mode
									$gateway_text='We\'re sorry but there were some problems processing your payment. Please contact us to upgrade your subscription';	// translate this
									require_once _BASEPATH_.'/includes/classes/log_error.class.php';
									new log_error(get_class($this),'Demo transaction when demo is not enabled: '.array2qs($input));
								}
							} else {	// paid price doesn't match the subscription price
								$gateway_text='We\'re sorry but the price you\'ve paid doesn\'t match the subscription price. Please contact us to upgrade your subscription';	// translate this
								require_once _BASEPATH_.'/includes/classes/log_error.class.php';
								new log_error(get_class($this),'Invalid amount paid: '.array2qs($input));
							}
						} else {	// if the subscr_id was not found
							$gateway_text='We\'re sorry but the system doesn\'t recognize the subscription for the payment you\'ve made. Please contact us to upgrade your subscription';	// translate this
							require_once _BASEPATH_.'/includes/classes/log_error.class.php';
							new log_error(get_class($this),'Invalid subscr_id received after payment: '.array2qs($input));
						}
					} else {	// if the user_id was not found
						$gateway_text='We\'re sorry but the system doesn\'t recognize the user for whom the payment was made. Please contact us to upgrade your subscription';	// translate this
						require_once _BASEPATH_.'/includes/classes/log_error.class.php';
						new log_error(get_class($this),'Invalid user_id received after payment: '.array2qs($input));
					}
				} else {
					$gateway_text='We\'re sorry but this transaction failed internal validation. Please try again.';	// translate this
					require_once _BASEPATH_.'/includes/classes/log_error.class.php';
					new log_error(get_class($this),'Invalid user_id received after payment: '.array2qs($input));
				}
			} else {
				$gateway_text=sprintf('We\'re sorry, but an error occurred when trying to process your Credit Card: %1s (%2s)',$input['x_response_reason_text'],$input['x_response_reason_code']);	// translate this
				require_once _BASEPATH_.'/includes/classes/log_error.class.php';
				new log_error(get_class($this),'Gateway error: '.$input['x_response_reason_text'].'('.$input['x_response_reason_code'].")\n".array2qs($input));
			}
		} else {
			$gateway_text='We\'re sorry, but an error occurred when trying to process your Credit Card. Please contact us for details or try again.';	// translate this
			require_once _BASEPATH_.'/includes/classes/log_error.class.php';
			new log_error(get_class($this),'Gateway error. Card not processed. '.array2qs($input));
		}
		$tpl->set_var('gateway_text',$gateway_text);
		return $myreturn;
	}


	function check_fraud($pay_result) {
		$fraud_managers=get_module_codes_by_type(_MODULE_FRAUD_);
		for ($i=0;isset($fraud_managers[$i]);++$i) {
			require_once(_BASEPATH_.'/plugins/fraud/'.$fraud_managers[$i].'/'.$fraud_managers[$i].'.class.php');
			$class='fraud_'.$fraud_managers[$i];
			$fraud=new $class;
			if ($fraud->is_fraud($pay_result)) {
				$this->is_fraud=true;
				$this->fraud_reason=$fraud->get_fraud_reason();
				break;
			}
		}
	}


	function _init() {
		$this->config=get_site_option(array(),$this->module_code);
	}
}
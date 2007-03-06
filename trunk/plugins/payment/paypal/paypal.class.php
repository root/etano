<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/payment/paypal/paypal.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/ipayment.class.php';

class payment_paypal extends ipayment {
	var $paypal_server='www.paypal.com';
	var $module_code='paypal';
	var $from_paypal=array('defaults'=>array(	'residence_country'=>'',
												'first_name'=>'',
												'last_name'=>'',
												'business'=>'',
												'receiver_email'=>'',
												'payer_email'=>'',
												'txn_id'=>'',
												'txn_type'=>'',
												'payment_status'=>'',
												'mc_gross'=>'',
												'mc_currency'=>'',
												'verify_sign'=>'',
												'test_ipn'=>0,
												'recurring'=>0,
												'item_number'=>0,
												'custom'=>0
											),
							'types'=>	array(	'residence_country'=>HTML_TEXTFIELD,
												'first_name'=>HTML_TEXTFIELD,
												'last_name'=>HTML_TEXTFIELD,
												'business'=>HTML_TEXTFIELD,
												'receiver_email'=>HTML_TEXTFIELD,
												'payer_email'=>HTML_TEXTFIELD,
												'txn_id'=>HTML_TEXTFIELD,
												'txn_type'=>HTML_TEXTFIELD,
												'payment_status'=>HTML_TEXTFIELD,
												'mc_gross'=>HTML_FLOAT,
												'mc_currency'=>HTML_TEXTFIELD,
												'verify_sign'=>HTML_TEXTFIELD,
												'test_ipn'=>HTML_INT,
												'recurring'=>HTML_INT,
												'item_number'=>HTML_INT,
												'custom'=>HTML_INT
											));

	function payment_paypal() {
		$this->ipayment();
		$this->_init();
	}


	function get_buy_button($payment=array()) {
		$this->_set_payment($payment);
		$myreturn='<form action="https://'.$this->form_page.'/cgi-bin/webscr" method="post" id="payment_paypal">
		<input type="hidden" name="cmd" value="_xclick-subscriptions" />
		<input type="hidden" name="business" value="'.$this->config['paypal_email'].'" />
		<input type="hidden" name="return" value="'._BASEURL_.'/thankyou.php?p=paypal" />
		<input type="hidden" name="notify_url" value="'._BASEURL_.'/processors/ipn.php?p=paypal" />
		<input type="hidden" name="cancel_return" value="'._BASEURL_.'" />
		<input type="hidden" name="item_name" value="'.$this->payment['subscr_name'].'" />
		<input type="hidden" name="item_number" value="'.$this->payment['subscr_id'].'" />
		<input type="hidden" name="custom" value="'.$this->payment['user_id'].'" />
		<input type="hidden" name="quantity" value="1" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="rm" value="2" />
		<input type="hidden" name="currency_code" value="'.$this->payment['currency'].'" />
		<input type="hidden" name="p3" value="'.$this->payment['duration'].'" />
		<input type="hidden" name="t3" value="'.$this->payment['duration_units'].'" />
		<input type="hidden" name="a3" value="'.$this->payment['price'].'" />
		<input type="hidden" name="sra" value="1" />';
		if ($this->payment['is_recurent']==1) {
			$myreturn.='<input type="hidden" name="src" value="1" />';
		}
		$myreturn.='<input type="submit" class="button" value="Buy with PayPal" />
		</form>';
		return $myreturn;
	}


	function redirect2gateway($payment=array()) {
		$this->_set_payment($payment);
		$topass=array(	'cmd'=>'_xclick-subscriptions',
						'business'=>$this->config['paypal_email'],
						'return'=>_BASEURL_.'/thankyou.php?p=paypal',
						'notify_url'=>_BASEURL_.'/processors/ipn.php?p=paypal',
						'cancel_return'=>_BASEURL_,
						'item_name'=>$this->payment['subscr_name'],
						'item_number'=>$this->payment['subscr_id'],
						'custom'=>$this->payment['user_id'],
						'quantity'=>1,
						'no_shipping'=>1,
						'no_note'=>1,
						'rm'=>2,
						'currency_code'=>$this->payment['currency'],
						'p3'=>$this->payment['duration'],
						't3'=>$this->payment['duration_units'],
						'a3'=>$this->payment['price'],
						'sra'=>1);
		if ($this->payment['is_recurent']==1) {
			$topass['src']=1;
		}
		post2page('https://'.$this->paypal_server.'/cgi-bin/webscr',$topass,true);
	}


	function thankyou(&$tpl) {
		$myreturn='';
		$tpl->set_file('gateway_text','thankyou_paypal.html');
		return $myreturn;
	}


	function ipn() {
		ob_start();
		print_r($_REQUEST);
		$debug_text=ob_get_contents();
		ob_end_clean();
		$fp=fopen('/tmp/ipn.txt','ab');
		fwrite($fp,$debug_text."\n-------\n\n");
		fclose($fp);

		header('Status: 200 OK');
		$myreturn=false;
		$gateway_text='';
		$dbtable_prefix=$GLOBALS['dbtable_prefix'];
		$input=array();
		foreach ($this->from_paypal['types'] as $k=>$v) {
			$input[$k]=sanitize_and_format_gpc($_POST,$k,$GLOBALS['__html2type'][$v],$GLOBALS['__html2format'][$v],$this->from_paypal['defaults'][$k]);
		}

		$postipn='cmd=_notify-validate&'.array2qs($_POST,array('p'));
		$header="POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header.='Host: '.$this->paypal_server."\r\n";
		$header.="Content-Type: application/x-www-form-urlencoded\r\n";
		$header.='Content-Length: '.strlen($postipn)."\r\n";
		$header.="Connection: close\r\n\r\n";
		$socket=fsockopen($this->paypal_server,80,$errno,$errstr,30);
		if ($socket) {
			fputs($socket,$header.$postipn."\r\n\r\n");
			$reply='';
			$headerdone=false;
			while(!feof($socket)) {
				$line=fgets($socket);
				if (strcmp($line,"\r\n")==0) {
					// read the header
					$headerdone=true;
				} elseif ($headerdone) {
					// header has been read. now read the contents
					$reply.=$line;
				}
			}
			fclose ($socket);
			$reply=trim($reply);
			if (strcasecmp($reply,'VERIFIED')==0 || strcasecmp($reply,'VERIFIED')!=0) {
				if (strcasecmp($input['business'],$this->config['paypal_email'])==0 || strcasecmp($input['receiver_email'],$this->config['paypal_email'])==0) {
					$query="SELECT `user_id` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id`='".$input['custom']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						$real_user=mysql_fetch_assoc($res);
						if (strcasecmp($input['txn_type'],'web_accept')==0 || strcasecmp($input['txn_type'],'send_money')==0 || strcasecmp($input['txn_type'],'subscr_payment')==0) {
							if (strcasecmp($input['payment_status'],'Completed')==0) {
								$query="SELECT `subscr_id`,`price`,`m_value_from`,`m_value_to`,`duration`,`duration_units` FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`='".$input['item_number']."' AND `is_visible`=1";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									$real_subscr=mysql_fetch_assoc($res);
									if (number_format($real_subscr['price'],2)==number_format($input['mc_gross'],2)) {
										if ($input['test_ipn']!=1 || ($this->config['demo_mode']==1 && $input['test_ipn']==1)) {
											require_once(_BASEPATH_.'/includes/iso3166.inc.php');
											$input['country']=isset($iso3166[$input['residence_country']]) ? $iso3166[$input['residence_country']] : '';
											$this->check_fraud($input);
											$query="SELECT `paid_until` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`='".$real_user['user_id']."' ORDER BY `paid_until` DESC LIMIT 1";
											if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
											$paid_from=date('Ymd');
											if (mysql_num_rows($res)) {
												$paid_from=mysql_result($res,0,0);
											}
											$query="INSERT INTO `{$dbtable_prefix}payments` SET `fk_user_id`='".$real_user['user_id']."',`_user`='".$_SESSION['user']['user']."',`gateway`='paypal',`fk_subscr_id`='".$real_subscr['subscr_id']."',`gw_txn`='".$input['txn_id']."',`name`='".$input['first_name'].' '.$input['last_name']."',`country`='".$input['country']."',`email`='".$input['payer_email']."',`m_value_from`='".$real_subscr['m_value_from']."',`m_value_to`='".$real_subscr['m_value_to']."',`amount_paid`='".$input['mc_gross']."',`is_suspect`='".(int)$this->is_fraud."',`suspect_reason`='".$this->fraud_reason."',`paid_from`='$paid_from'";
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
												$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `membership`='".$real_subscr['m_value_to']."' WHERE `user_id`='".$real_user['user_id']."'";
												if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
												$myreturn=true;
											}
										} else {
											// a demo transaction when we're not in demo mode
											require_once _BASEPATH_.'/includes/classes/log_error.class.php';
											new log_error(get_class($this),'Demo transaction when demo is not enabled: '.array2qs($_POST));
										}
									} else {
										// paid price doesn't match the subscription price
										require_once _BASEPATH_.'/includes/classes/log_error.class.php';
										new log_error(get_class($this),'Invalid amount paid: '.array2qs($_POST));
									}
								} else {
									// if the subscr_id was not found
									require_once _BASEPATH_.'/includes/classes/log_error.class.php';
									new log_error(get_class($this),'Invalid subscr_id received after payment: '.array2qs($_POST));
								}
							} else {
								require_once _BASEPATH_.'/includes/classes/log_error.class.php';
								new log_error(get_class($this),'Payment status not Completed: '.$input['payment_status']."\n".array2qs($_POST));
							}
						} elseif (strcasecmp($input['txn_type'],'subscr_eot')==0) {
							$query="SELECT `payment_id` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`='".$real_user['user_id']."' AND `fk_subscr_id`='".$input['item_number']."' ORDER BY `payment_id` DESC LIMIT 1";
							if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							if (mysql_num_rows($res)) {
								$payment_id=mysql_result($res,0,0);
								$query="UPDATE `{$dbtable_prefix}payments` SET `paid_until`=curdate() WHERE `payment_id`='$payment_id'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							} else {
								// invalid eot.
							}
						} else {
							// unhandled txn_type
							require_once _BASEPATH_.'/includes/classes/log_error.class.php';
							new log_error(get_class($this),'Unhandled txn_type (probably not an error): '.$input['txn_type']."\n".array2qs($_POST));
						}
					} else {
						// if the user_id was not found
						require_once _BASEPATH_.'/includes/classes/log_error.class.php';
						new log_error(get_class($this),'Invalid user_id received after payment: '.array2qs($_POST));
					}
				} else {
					require_once _BASEPATH_.'/includes/classes/log_error.class.php';
					new log_error(get_class($this),'Payment was not made into our account: '.array2qs($_POST));
				}
			} elseif (strcasecmp($reply,'INVALID')==0) {
				require_once _BASEPATH_.'/includes/classes/log_error.class.php';
				new log_error(get_class($this),'Transaction verification with paypal server failed as invalid: '.array2qs($_POST));
			} else {
				require_once _BASEPATH_.'/includes/classes/log_error.class.php';
				new log_error(get_class($this),'Transaction verification with paypal server failed with unknown code: '.$reply.' '.array2qs($_POST));
			}
		} else {
			// socket problem
			require_once _BASEPATH_.'/includes/classes/log_error.class.php';
			new log_error(get_class($this),'Connection to paypal server failed. '.array2qs($_POST));
		}
	}


	function _init() {
		$this->config=get_site_option(array(),$this->module_code);
		if ($this->config['demo_mode']==1) {
			$this->paypal_server='www.sandbox.paypal.com';
//			$this->paypal_server='www.eliteweaver.co.uk';
		}
	}
}
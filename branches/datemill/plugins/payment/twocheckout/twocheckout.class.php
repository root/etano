<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/payment/twocheckout/twocheckout.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
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
											'dm_item_type'=>'',
											'internal_id'=>0,
											'user_id'=>0),
						'types'=>	array(	'x_2checked'=>FIELD_TEXTFIELD,
											'x_MD5_Hash'=>FIELD_TEXTFIELD,
											'x_trans_id'=>FIELD_TEXTFIELD,
											'x_amount'=>FIELD_FLOAT,
											'card_holder_name'=>FIELD_TEXTFIELD,
											'x_Country'=>FIELD_TEXTFIELD,
											'x_City'=>FIELD_TEXTFIELD,
											'x_State'=>FIELD_TEXTFIELD,
											'x_Zip'=>FIELD_TEXTFIELD,
											'x_Address'=>FIELD_TEXTFIELD,
											'x_Email'=>FIELD_TEXTFIELD,
											'x_Phone'=>FIELD_TEXTFIELD,
											'demo'=>FIELD_TEXTFIELD,
											'x_response_code'=>FIELD_INT,
											'x_response_reason_code'=>FIELD_TEXTFIELD,
											'x_response_reason_text'=>FIELD_TEXTFIELD,
											'dm_item_type'=>FIELD_TEXTFIELD,
											'internal_id'=>FIELD_INT,
											'user_id'=>FIELD_INT));

	function payment_twocheckout() {
		$this->ipayment();
		$this->_init();
	}

	// $payment['dm_item_type'] must be one of 'subscr','prod'
	function get_buy_button($payment=array()) {
		$this->_set_payment($payment);
		$myreturn='<form action="https://www2.2checkout.com/2co/buyer/purchase" method="post">
		<input type="hidden" name="x_login" value="'.$this->config['sid'].'" />
		<input type="hidden" name="id_type" value="1" />
		<input type="hidden" name="fixed" value="Y" />
		<input type="hidden" name="pay_method" value="CC" />
		<input type="hidden" name="x_receipt_link_url" value="'._BASEURL_.'/thankyou.php?p='.$this->module_code.'" />
		<input type="hidden" name="x_invoice_num" value="inv'.gen_pass(5).'" />
		<input type="hidden" name="x_amount" value="'.$this->payment['price'].'" />
		<input type="hidden" name="c_price" value="'.$this->payment['price'].'" />
		<input type="hidden" name="c_prod" value="'.$this->payment['internal_id'].'" />
		<input type="hidden" name="c_name" value="'.$this->payment['internal_name'].'" />
		<input type="hidden" name="c_description" value="'.$this->payment['internal_diz'].'" />
		<input type="hidden" name="c_tangible" value="N" />
		<input type="hidden" name="internal_id" value="'.$this->payment['internal_id'].'" />
		<input type="hidden" name="dm_item_type" value="'.$this->payment['dm_item_type'].'" />';
		if (!empty($this->payment['user_id'])) {
			$myreturn.='<input type="hidden" name="user_id" value="'.$this->payment['user_id'].'" />';
		}
		if ($this->config['demo_mode']==1) {
			$myreturn.='<input type="hidden" name="demo" value="Y" />';
		}
		$myreturn.='<input name="submit" class="button" type="submit" value="Buy from 2Checkout" /></form>';
		return $myreturn;
	}


	function redirect2gateway($payment=array()) {
		$this->_set_payment($payment);
		$qs=array(	'x_login'=>$this->config['sid'],
					'id_type'=>1,
					'fixed'=>'Y',
					'pay_method'=>'CC',
					'x_receipt_link_url'=>_BASEURL_.'/thankyou.php?p='.$this->module_code,
					'x_invoice_num'=>'inv'.gen_pass(5),
					'x_amount'=>$this->payment['price'],
					'c_price'=>$this->payment['price'],
					'c_prod'=>$this->payment['internal_id'],
					'c_name'=>$this->payment['internal_name'],
					'c_description'=>$this->payment['internal_diz'],
					'c_tangible'=>'N',
					'internal_id'=>$this->payment['internal_id'],
					'dm_item_type'=>$this->payment['dm_item_type'],
				);
		if ($this->config['demo_mode']==1) {
			$qs['demo']='Y';
		}
		if (!empty($this->payment['user_id'])) {
			$qs['user_id']=$this->payment['user_id'];
		}
//		redirect2page('https://www2.2checkout.com/2co/buyer/purchase',array(),array2qs($qs),true);
		post2page('https://www2.2checkout.com/2co/buyer/purchase',$qs,true);
	}


	function thankyou(&$tpl) {
		$myreturn=false;
		$gateway_text='';
		global $dbtable_prefix;
		$input=array();
		$output=array();
		foreach ($this->from_tco['types'] as $k=>$v) {
			$input[$k]=sanitize_and_format_gpc($_POST,$k,$GLOBALS['__field2type'][$v],$GLOBALS['__field2format'][$v],$this->from_tco['defaults'][$k]);
		}
		$input['x_amount']=number_format($input['x_amount'],2,'.','');
		$input['x_Email']=strtolower($input['x_Email']);
		$input['card_holder_name']=ucwords(strtolower($input['card_holder_name']));

		if (strcasecmp($input['x_2checked'],'Y')==0) {
			if ($this->config['demo_mode']==1 && strcasecmp($input['demo'],'Y')==0) {
				$input['x_trans_id']=1;
			}
			if ($input['x_response_code']==1) {	// processed ok
				if (strcasecmp($input['x_MD5_Hash'],strtoupper(md5($this->config['secret'].$this->config['sid'].$input['x_trans_id'].$input['x_amount'])))==0) {
					if ($input['dm_item_type']=='subscr') {
						$query="SELECT `".USER_ACCOUNT_ID."` as `user_id`,`".USER_ACCOUNT_USER."` as `user` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."`=".$input['user_id'];
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						if (mysql_num_rows($res)) {
							$real_user=mysql_fetch_assoc($res);
							$query="SELECT `subscr_id`,`price`,`m_value_to`,`duration` FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`=".$input['internal_id']." AND `is_visible`=1";
							if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							if (mysql_num_rows($res)) {
								$real_subscr=mysql_fetch_assoc($res);
								if (number_format($real_subscr['price'],2)==number_format($input['x_amount'],2)) {
									if (strcasecmp($input['demo'],'Y')!=0 || ($this->config['demo_mode']==1 && strcasecmp($input['demo'],'Y')==0)) {
										require_once _BASEPATH_.'/includes/iso31661a3.inc.php';
										if (isset($iso31661a3[$input['x_Country']])) {
											$input['country']=$iso31661a3[$input['x_Country']];	// needed for the fraud check
											$input['email']=$input['x_Email'];
											$this->check_fraud($input);
										} else {
											$this->is_fraud=true;
											$this->fraud_reason='Invalid country code received from 2CheckOut. Please contact administrator.';
											require_once _BASEPATH_.'/includes/classes/log_error.class.php';
											new log_error(array('module_name'=>get_class($this),'text'=>'country code received from 2co not found in iso31661a3.inc.php file'.array2qs($_POST)));
										}
										if (!empty($real_subscr['duration'])) {
											// if the old subscription is not over yet, we need to extend the new one with some days
											$query="SELECT a.`payment_id`,UNIX_TIMESTAMP(a.`paid_until`) as `paid_until`,b.`price`,b.`duration` FROM `{$dbtable_prefix}payments` a LEFT JOIN `{$dbtable_prefix}subscriptions` b ON a.`fk_subscr_id`=b.`subscr_id` WHERE a.`fk_user_id`=".$real_user['user_id']." AND a.`refunded`=0 AND a.`is_active`=1 AND a.`is_subscr`=1 AND a.`m_value_to`>2 ORDER BY a.`paid_until` DESC LIMIT 1";
											if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
											if (mysql_num_rows($res)) {
												$rsrow=mysql_fetch_assoc($res);
												if ((int)$rsrow['paid_until']>(int)time()) {
													$remaining_days=((int)$rsrow['paid_until']-(int)time())/86400;  //86400 seconds in a day
													if ($remaining_days>0) {
														$remaining_value=(((int)$rsrow['price'])/((int)$rsrow['duration']))*$remaining_days;
														$day_value_new=((int)$real_subscr['price'])/((int)$real_subscr['duration']);
														$days_append=round($remaining_value/$day_value_new);
														$real_subscr['duration']=(int)$real_subscr['duration'];
														$real_subscr['duration']+=$days_append;
													}
												}
											}
										}
										// all old active subscriptions end now!
										$query="UPDATE `{$dbtable_prefix}payments` SET `paid_until`=CURDATE(),`is_active`=0 WHERE `fk_user_id`=".$real_user['user_id']." AND `is_active`=1 AND `is_subscr`=1";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										// insert the new subscription
										$query="INSERT INTO `{$dbtable_prefix}payments` SET `is_active`=1,`fk_user_id`=".$real_user['user_id'].",`_user`='".$real_user['user']."',`gateway`='".$this->module_code."',`is_subscr`=1,`fk_subscr_id`='".$real_subscr['subscr_id']."',`gw_txn`='".$input['x_trans_id']."',`name`='".$input['card_holder_name']."',`country`='".$input['x_Country']."',`state`='".$input['x_State']."',`city`='".$input['x_City']."',`zip`='".$input['x_Zip']."',`street_address`='".$input['x_Address']."',`email`='".$input['x_Email']."',`phone`='".$input['x_Phone']."',`m_value_to`=".$real_subscr['m_value_to'].",`amount_paid`='".$input['x_amount']."',`is_suspect`=".((int)$this->is_fraud).",`suspect_reason`='".addslashes($this->fraud_reason)."',`date`=now(),`paid_from`=CURDATE()";
										if (!empty($real_subscr['duration'])) {
											$query.=",`paid_until`=CURDATE()+INTERVAL ".$real_subscr['duration'].' DAY';
										}
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										if (!$this->is_fraud) {
											$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `membership`=".$real_subscr['m_value_to']." WHERE `".USER_ACCOUNT_ID."`=".$real_user['user_id'];
											if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
											$myreturn=true;
											$gateway_text='';
											add_member_score($real_user['user_id'],'payment');
										} else {
											// DEPT_ADMIN from includes/admin_functions.inc.php is hardcoded below as 4
											$query="SELECT `email` FROM `{$dbtable_prefix}admin_accounts` WHERE `dept_id`=4 ORDER BY `admin_id` DESC LIMIT 1";
											if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
											if (mysql_num_rows($res)) {
												send_template_email(mysql_result($res,0,0),'Possible fraud detected, please investigate','','',array(),$this->fraud_reason);
											}
										}
									} else {	// a demo transaction when we're not in demo mode
										$gateway_text='We\'re sorry but there were some problems processing your payment. Please contact us to upgrade your subscription';	// translate this
										require_once _BASEPATH_.'/includes/classes/log_error.class.php';
										new log_error(array('module_name'=>get_class($this),'text'=>'Demo transaction when demo is not enabled: '.array2qs($input)));
									}
								} else {	// paid price doesn't match the subscription price
									$gateway_text='We\'re sorry but the price you\'ve paid doesn\'t match the subscription price. Please contact us to upgrade your subscription';	// translate this
									require_once _BASEPATH_.'/includes/classes/log_error.class.php';
									new log_error(array('module_name'=>get_class($this),'text'=>'Invalid amount paid: '.array2qs($input)));
								}
							} else {	// if the subscr_id was not found
								$gateway_text='We\'re sorry but the system doesn\'t recognize the subscription for the payment you\'ve made. Please contact us to upgrade your subscription';	// translate this
								require_once _BASEPATH_.'/includes/classes/log_error.class.php';
								new log_error(array('module_name'=>get_class($this),'text'=>'Invalid subscr_id received after payment: '.array2qs($input)));
							}
						} else {	// if the user_id was not found
							$gateway_text='We\'re sorry but the system doesn\'t recognize the user for whom the payment was made. Please contact us to upgrade your subscription';	// translate this
							require_once _BASEPATH_.'/includes/classes/log_error.class.php';
							new log_error(array('module_name'=>get_class($this),'text'=>'Invalid user_id received after payment: '.array2qs($input)));
						}

					} elseif ($input['dm_item_type']=='prod') {

						$real_user=array();
						if (!empty($input['user_id'])) {
							$query="SELECT `fk_user_id` as `user_id`,`_user` as `user`,`f2` as `email1`,`f7` as `email2`,`f8` as `email3` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$input['user_id'];
							if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							if (mysql_num_rows($res)) {
								$real_user=mysql_fetch_assoc($res);
							}
						}
						// alternate method of figuring out if we already have this customer in the db
						if (empty($real_user['user_id']) && !empty($input['x_Email'])) {
							$query="SELECT `fk_user_id` as `user_id`,`_user` as `user`,`f2` as `email1`,`f7` as `email2`,`f8` as `email3` FROM `{$dbtable_prefix}user_profiles` WHERE `f2`='".$input['x_Email']."' OR `f7`='".$input['x_Email']."' OR `f8`='".$input['x_Email']."' LIMIT 1";
							if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							if (mysql_num_rows($res)) {
								$real_user=mysql_fetch_assoc($res);
							}
						}
						$query="SELECT `prod_id`,`price`,`bundle_of` FROM `products` WHERE `prod_id`=".$input['internal_id'];
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						if (mysql_num_rows($res)) {
							$real_prod=mysql_fetch_assoc($res);
							if (number_format($real_prod['price'],2)==number_format($input['x_amount'],2)) {
								if (strcasecmp($input['demo'],'Y')!=0 || ($this->config['demo_mode']==1 && strcasecmp($input['demo'],'Y')==0)) {
									require_once _BASEPATH_.'/includes/iso31661a3.inc.php';
									if (isset($iso31661a3[$input['x_Country']])) {
										$input['country']=$iso31661a3[$input['x_Country']];	// needed for the fraud check
										$input['email']=$input['x_Email'];
										$this->check_fraud($input);
									} else {
										$this->is_fraud=true;
										$this->fraud_reason='Invalid country code received from 2CheckOut. Please contact administrator.';
										require_once _BASEPATH_.'/includes/classes/log_error.class.php';
										new log_error(array('module_name'=>get_class($this),'text'=>'country code received from 2co not found in iso31661a3.inc.php file'.array2qs($_POST)));
									}
									// insert the new payment
									$query="INSERT INTO `{$dbtable_prefix}payments` SET `gateway`='".$this->module_code."',`is_subscr`=0,`is_active`=0,`fk_subscr_id`=0,`gw_txn`='".$input['x_trans_id']."',`name`='".$input['card_holder_name']."',`country`='".$input['x_Country']."',`state`='".$input['x_State']."',`city`='".$input['x_City']."',`zip`='".$input['x_Zip']."',`street_address`='".$input['x_Address']."',`email`='".$input['x_Email']."',`phone`='".$input['x_Phone']."',`amount_paid`='".$input['x_amount']."',`is_suspect`=".((int)$this->is_fraud).",`suspect_reason`='".addslashes($this->fraud_reason)."',`date`=now()";
									if (isset($real_user['user_id'])) {
										$query.=",`fk_user_id`=".$real_user['user_id'].",`_user`='".$real_user['user']."'";
									}
									if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									$payment_id=mysql_insert_id();

									$prods=array();
									if (!empty($real_prod['bundle_of'])) {
										$prods=explode('|',$real_prod['bundle_of']);
									} else {
										$prods[]=$real_prod['prod_id'];
									}
									// if this is a new customer, create an account and profile for him
									if (empty($real_user['user_id'])) {
										$input['pass']=gen_pass(6);
										$query="INSERT IGNORE INTO `".USER_ACCOUNTS_TABLE."` SET `".USER_ACCOUNT_USER."`='".$input['x_Email']."',`".USER_ACCOUNT_PASS."`=md5('".$input['pass']."'),`email`='".$input['x_Email']."',`membership`=4,`status`=";
										if ($this->is_fraud) {
											$query.=ASTAT_SUSPENDED;
										} else {
											$query.=ASTAT_ACTIVE;
										}
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										$real_user['user_id']=mysql_insert_id();
										$real_user['user']=$input['x_Email'];
										$now=gmdate('YmdHis');
										$query="INSERT IGNORE INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$real_user['user_id']."',`_user`='".$real_user['user']."',`last_changed`='$now',`date_added`='$now',`status`=".STAT_APPROVED.",`f1`='".$input['card_holder_name']."',`f2`='".$input['x_Email']."',`score`='".$input['x_amount']."'";
										if ($this->is_fraud) {
											$query.=",`f11`=1";	// is_blocked
										}
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										// update the payment with this user_id
										$query="UPDATE `{$dbtable_prefix}payments` SET `fk_user_id`=".$real_user['user_id'].",`_user`='".$real_user['user']."' WHERE `payment_id`=$payment_id";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									} else {
										// older customer found. Update score and email
										$query="UPDATE `{$dbtable_prefix}user_profiles` SET `score`=`score`+".$input['x_amount'];
										if ($input['x_Email']!=$real_user['email1'] && $input['x_Email']!=$real_user['email2'] && $input['x_Email']!=$real_user['email3']) {
											if (empty($real_user['email2'])) {
												$query.=",`f7`='".$input['x_Email']."'";
											} elseif (empty($real_user['email3'])) {
												$query.=",`f8`='".$input['x_Email']."'";
											} else {
												$query.=",`f9`=concat(`f9`,'".$input['x_Email']."\n')";
											}
										}
										$query.=" WHERE `fk_user_id`=".$real_user['user_id'];
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									}
									// if he bought Etano, create a new site
									if (in_array(ETANO_PROD_ID,$prods)) {
										$query="INSERT INTO `user_sites` SET `fk_user_id`=".$real_user['user_id'].",`active`=".((int)(!$this->is_fraud));
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										$input['site_id']=mysql_insert_id();
										$input['license']=gen_license(array('site_id'=>$input['site_id'],'name'=>$input['card_holder_name']));
										$output['license']=$input['license'];
										$query="UPDATE `user_sites` SET `license`='".$input['license']."',`license_md5`=md5('".$input['license']."') WHERE `site_id`=".$input['site_id'];
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									} else {
										$query="SELECT `site_id`,`license` FROM `user_sites` WHERE `fk_user_id`=".$real_user['user_id']." LIMIT 1";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										if (mysql_num_rows($res)) {
											$input=array_merge($input,mysql_fetch_assoc($res));
										} else {	// something must be wrong here....
											$input['site_id']=0;
											$input['license']='';
										}
									}

									// insert all bought products into db
									$query="INSERT INTO `user_products` (`fk_prod_id`,`fk_site_id`,`fk_user_id`,`processor`,`orderno`,`date_purchased`,`license`,`license_md5`) VALUES ";
									for ($i=0;isset($prods[$i]);++$i) {
										$query.="(".$prods[$i].",".$input['site_id'].",".$real_user['user_id'].",'".$this->module_code."','".$input['x_trans_id']."',now()";
										if ($prods[$i]==ETANO_PROD_ID) {
											$query.=",'".$input['license']."','".md5($input['license'])."'";
										} else {
											$query.=",'',''";
										}
										$query.="),";
									}
									$query=substr($query,0,-1);
									if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

									// show the thank you page with all required details.
									if (!$this->is_fraud) {
										$output['name']=$input['card_holder_name'];
										$output['license_md5']=md5($input['license']);
										$output['prod_id']=$real_prod['prod_id'];
										if (isset($input['pass'])) {
											$output['pass']=$input['pass'];
										}
										$output['user']=$real_user['user'];
										$output['email']=$input['x_Email'];
										$tpl->set_file('gateway_text','gateway_ok.html');
										$tpl->set_var('output',$output);
										$tpl->process('gateway_text','gateway_text',TPL_OPTIONAL);
										$tpl->drop_var('output');
										send_template_email($input['x_Email'],sprintf('Your %s purchase details',_SITENAME_),'','',array(),$tpl->get_var_silent('gateway_text'));
									} else {
										$tpl->set_file('gateway_text','gateway_nok.html');
										$output['email']=$input['x_Email'];
										$output['name']=$input['card_holder_name'];
										$tpl->set_var('output',$output);
										$tpl->process('gateway_text','gateway_text');
										// DEPT_ADMIN from includes/admin_functions.inc.php is hardcoded below as 4
										$query="SELECT `email` FROM `{$dbtable_prefix}admin_accounts` WHERE `dept_id`=4 ORDER BY `admin_id` DESC LIMIT 1";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										if (mysql_num_rows($res)) {
											send_template_email(mysql_result($res,0,0),'Possible fraud detected, please investigate','','',array(),$this->fraud_reason);
										}
									}
								} else {	// a demo transaction when we're not in demo mode
									$gateway_text='We\'re sorry but there were some problems processing your payment. Please contact us to finalize the payment.';	// translate this
									require_once _BASEPATH_.'/includes/classes/log_error.class.php';
									new log_error(array('module_name'=>get_class($this),'text'=>'Demo transaction when demo is not enabled: '.array2qs($input)));
								}
							} else {	// paid price doesn't match the product price
								$gateway_text='We\'re sorry but the price you\'ve paid doesn\'t match the product price. Please contact us if you feel this is an error.';	// translate this
								require_once _BASEPATH_.'/includes/classes/log_error.class.php';
								new log_error(array('module_name'=>get_class($this),'text'=>'Invalid amount paid: '.array2qs($input)));
							}
						} else {	// if the prod_id was not found
							$gateway_text='We\'re sorry but the system doesn\'t recognize the subscription for the payment you\'ve made. Please contact us to upgrade your subscription';	// translate this
							require_once _BASEPATH_.'/includes/classes/log_error.class.php';
							new log_error(array('module_name'=>get_class($this),'text'=>'Invalid prod_id received after payment: '.array2qs($input)));
						}
					} else {	// dm_item_type is neither 'prod' nor 'subscr'
						$gateway_text='Invalid payment received. Please contact us if you feel this is an error.';	// translate this
						require_once _BASEPATH_.'/includes/classes/log_error.class.php';
						new log_error(array('module_name'=>get_class($this),'text'=>'Invalid dm_item_type: '.array2qs($input)));
					}
				} else {
					$gateway_text='We\'re sorry but this transaction failed internal validation. Please try again.';	// translate this
					require_once _BASEPATH_.'/includes/classes/log_error.class.php';
					new log_error(array('module_name'=>get_class($this),'text'=>'Invalid hash code received after payment: '.array2qs($input).'. My hash:'.strtoupper(md5($this->config['secret'].$this->config['sid'].$input['x_trans_id'].$input['x_amount']))));
				}
			} else {
				$gateway_text=sprintf('We\'re sorry, but an error occurred when trying to process your Credit Card: %1$s (%2$s)',$input['x_response_reason_text'],$input['x_response_reason_code']);	// translate this
				require_once _BASEPATH_.'/includes/classes/log_error.class.php';
				new log_error(array('module_name'=>get_class($this),'text'=>'Gateway error: '.$input['x_response_reason_text'].'('.$input['x_response_reason_code'].")\n".array2qs($input)));
			}
		} else {
			$gateway_text='We\'re sorry, but an error occurred when trying to process your Credit Card. Please contact us for details or try again.';	// translate this
			require_once _BASEPATH_.'/includes/classes/log_error.class.php';
			new log_error(array('module_name'=>get_class($this),'text'=>'Gateway error. Card not processed. '.array2qs($input)));
		}
		if (!empty($gateway_text)) {
			$tpl->set_var('gateway_text',$gateway_text);
		}
		return $myreturn;
	}


	function check_fraud($pay_result) {
		$fraud_managers=get_module_codes_by_type(MODULE_FRAUD);
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

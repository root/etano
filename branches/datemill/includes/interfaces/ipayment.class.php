<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/interfaces/ipayment.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

class ipayment {
	var $config=array();
	var $payment=array();
	var $module_code='';
	var $is_fraud=false;
	var $fraud_reason='';

	function ipayment() {
	}

	function get_buy_button() {
		return '';
	}

	function redirect2gateway() {
	}

	function thankyou() {
		return '';
	}


	function ipn() {
	}


	function check_fraud($pay_result) {
	}


	function _init() {
	}


	function _set_payment($payment) {
		if (!empty($payment)) {
			$this->payment=$payment;
		}
	}
}

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

abstract class ipayment {
	var $config=array();
	var $payment=array();
	var $module_code='';
	var $is_fraud=false;
	var $fraud_reason='';

	function get_buy_button($payment=array()) {
		return '';
	}

	function redirect2gateway($payment=array()) {
	}

	function thankyou(&$tpl) {
		return '';
	}


	function ipn() {
	}


	function check_fraud($pay_result) {
	}


	protected function _init() {
	}


	function _set_payment($payment) {
		if (!empty($payment)) {
			$this->payment=$payment;
		}
	}
}

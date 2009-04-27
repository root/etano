<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/interfaces/ifraud.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

abstract class ifraud {
	var $config=array();
	var $module_code='';
	var $fraud_reason='';

	function is_fraud() {
		return false;
	}

	function get_fraud_reason() {
		return $this->fraud_reason;
	}

	function set_fraud_reason($fraud_reason) {
		$this->fraud_reason=$fraud_reason;
	}

	protected function _init() {
	}
}

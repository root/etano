<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/interfaces/ifraud.class.php
$Revision: 64 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

class ifraud {
	var $config=array();
	var $module_code='';
	var $fraud_reason='';

	function ifraud() {
	}

	function is_fraud() {
		return false;
	}

	function get_fraud_reason() {
		return $this->fraud_reason;
	}

	function set_fraud_reason($fraud_reason) {
		$this->fraud_reason=$reason;
	}

	function _init() {
	}
}
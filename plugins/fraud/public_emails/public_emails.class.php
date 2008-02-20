<?php
require_once _BASEPATH_.'/includes/interfaces/ifraud.class.php';

class fraud_public_emails extends ifraud {
	var $module_code='public_emails';


	function fraud_public_emails() {
		$this->ifraud();
		$this->_init();
	}


	function is_fraud($pay_result) {
		$myreturn=false;
		for ($i=0;isset($this->config['banned_strings'][$i]);++$i) {
			if (strpos($pay_result['email'],$this->config['banned_strings'][$i])) {
				$myreturn=true;
				$this->set_fraud_reason('Email from public domains: '.$pay_result['email']);
				break;
			}
		}
		return $myreturn;
	}


	function _init() {
//		$this->config=get_site_option(array(),$this->module_code);
		$this->config['banned_strings']=array('@yahoo','@hotmail','@gmail','@msn','@excite','@home.ro','@go.ro','@manele','@bestscriptvn.biz');
	}
}

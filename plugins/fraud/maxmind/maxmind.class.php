<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/fraud/maxmind/maxmind.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

if (!defined('_LICENSE_KEY_')) {
	die('Hacking attempt');
}

require_once _BASEPATH_.'/includes/interfaces/ifraud.class.php';

class fraud_maxmind extends ifraud {
	var $module_code='maxmind';


	function __construct() {
		$this->_init();
	}


	function is_fraud(&$pay_result) {
		$myreturn=false;
		$errno=0;
		$errstr='';
		if (isset($pay_result['country']) && isset($_SERVER['REMOTE_ADDR'])) {
			$socket=fsockopen('www.maxmind.com',8010,$errno,$errstr,30);
			if ($socket) {
				$header='GET /a?l='.$this->config['license_key'].'&i='.$_SERVER['REMOTE_ADDR']." HTTP/1.0\r\n";
				$header.="Host: www.maxmind.com\r\n";
				$header.="Connection: close\r\n";
					fputs($socket,$header."\r\n");
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
				fclose($socket);
				$reply=trim($reply);
				require_once _BASEPATH_.'/includes/iso31661a2.inc.php';
				if (isset($GLOBALS['iso31661a2'][$reply])) {
					if (strcasecmp($GLOBALS['iso31661a2'][$reply],$pay_result['country'])!=0) {
						$myreturn=true;
						$this->set_fraud_reason('Credit card from: '.$pay_result['country'].'. User IP from: '.$GLOBALS['iso31661a2'][$reply]);
					}
				} else {
					$myreturn=true;
					$this->set_fraud_reason('Invalid country code for your IP address. Please contact administrator.');
					require_once _BASEPATH_.'/includes/classes/log_error.class.php';
					new log_error(array('module_name'=>get_class($this),'text'=>$reply.' country code not found in iso31661a2.inc.php file or invalid answer from maxmind'));
				}
			} else {
				require_once _BASEPATH_.'/includes/classes/log_error.class.php';
				new log_error(array('module_name'=>get_class($this),'text'=>'Unable to connect to maxmind server: '.$errstr));
			}
		}
		return $myreturn;
	}


	function _init() {
		$this->config=get_site_option(array(),$this->module_code);
	}
}

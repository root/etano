<?php
define('_ERRORLOG_DB_',0);
define('_ERRORLOG_FILE_',1);
define('_ERRORLOG_STDOUT_',2);

class log_error {
	var $config=array();
	var $module_code='log';

	function log_error($module_name,$error) {
		$this->_init();
		if ($this->config['log_mode']==_ERRORLOG_DB_) {
			$dbtable_prefix=$GLOBALS['dbtable_prefix'];
			$query="INSERT IGNORE INTO `{$dbtable_prefix}error_log` SET `module`='$module_name',`error`='".sanitize_and_format($error,TYPE_STRING,FORMAT_ADDSLASH)."'";
			@mysql_query($query);
		} elseif ($this->config['log_mode']==_ERRORLOG_FILE_) {
			error_log(date().': '.$module_name.': '.$error."\n\n",3,$this->config['file_log']);
		} elseif ($this->config['log_mode']==_ERRORLOG_STDOUT_) {
			echo $module_name.': '.$error;
		}
		if ($this->config['log_mode']!=_ERRORLOG_STDOUT_ && defined('_DEBUG_') && _DEBUG_!=0) {
			echo $module_name.': '.$error;
		}
	}

	function _init() {
//		$this->config=get_site_option(array(),$this->module_code);
		$this->config['log_mode']=_ERRORLOG_DB_;
		$this->config['file_log']=_BASEPATH_.'/tmp/log_error.txt';
	}
}
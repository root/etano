<?php
define('_ERRORLOG_DB_',0);
define('_ERRORLOG_FILE_',1);
define('_ERRORLOG_STDOUT_',2);

class log_error {
	var $config=array();
	var $module_code='log';

	function log_error($error,$config=array()) {
		$this->_init();
		$this->config=array_merge($this->config,$config);
		$error=array_merge(array('module_name'=>'','text'=>''),$error);
		if ($this->config['log_mode']==_ERRORLOG_DB_) {
			global $dbtable_prefix;
			$query="INSERT IGNORE INTO `{$dbtable_prefix}error_log` SET `module`='".$error['module_name']."',`error`='".sanitize_and_format($error['text'],TYPE_STRING,FORMAT_ADDSLASH)."',`error_date`='".gmdate('YmdHis')."'";
			@mysql_query($query);
		} elseif ($this->config['log_mode']==_ERRORLOG_FILE_) {
			error_log("\n-------\n".date('Y-m-d H:i:s',time()).': '.$error['module_name'].': '.$error['text']."\n\n",3,$this->config['file_log']);
		} elseif ($this->config['log_mode']==_ERRORLOG_STDOUT_) {
			echo $error['module_name'].': '.$error['text'];
		}
	}

	function _init() {
//		$this->config=get_site_option(array(),$this->module_code);
		$this->config['log_mode']=_ERRORLOG_DB_;
		$this->config['file_log']=_BASEPATH_.'/tmp/log_error.txt';
	}
}
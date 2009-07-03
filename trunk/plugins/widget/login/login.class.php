<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/login/login.class.php
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

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_login extends icontent_widget {
	var $module_code='widget_login';

	function __construct() {
		$this->_init();
		if (func_num_args()==1) {
			$more_args=func_get_arg(0);
			$this->config=array_merge($this->config,$more_args);
		}
	}


	function display(&$tpl) {
		$this->tpl=$tpl;
		$this->_title($this->config['module_name']);
		$this->_content();
		return $this->_finish_display();
	}


	function _title($title='') {
		$this->tpl->set_var('widget.title',$title);
	}


	protected function _content() {
		$this->tpl->set_file('widget.content','widgets/login/display.html');
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$this->tpl->set_var('tplvars',$GLOBALS['tplvars']);
		$myreturn=$this->tpl->process('widget.content','widget.content',TPL_OPTIONAL);
		$this->tpl->drop_var('widget');
		return $myreturn;
	}


	function process() {
	}


	function settings_display() {
		return '';
	}


	function settings_process() {
	}


	protected function _init() {
		$this->config['module_name']='Already a member? Log in';
	}
}

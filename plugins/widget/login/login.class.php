<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/widget/login/login.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_login extends icontent_widget {
	var $module_code='widget_login';

	function widget_login() {
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
		$this->tpl->set_var('widget_title',$title);
	}


	function _content() {
		$this->tpl->set_file('widget_content','widgets/login/display.html');
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$myreturn=$this->tpl->process('','widget_content',TPL_OPTIONAL);
		$this->tpl->drop_var('widget_title');
		$this->tpl->drop_var('widget_content');
		$this->tpl->drop_var('widget_bottom');
		$this->tpl->drop_var('temp');
		return $myreturn;
	}


	function process() {
	}


	function settings_display() {
		return '';
	}


	function settings_process() {
	}


	function _init() {
		$this->config['module_name']='Already a member? Log in';
	}
}

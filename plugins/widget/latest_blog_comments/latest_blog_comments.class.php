<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/latest_blog_comments/latest_blog_comments.class.php
$Revision: 327 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_latest_blog_comments extends icontent_widget {
	var $module_code='latest_blog_comments';

	function widget_latest_blog_comments() {
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


	function _content() {
		if (is_file(_BASEPATH_.'/skins_site/'.get_my_skin().'/cache/widgets/latest_blog_comments/display.html')) {
			$this->tpl->set_file('widget.content','cache/widgets/latest_blog_comments/display.html');
		}
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$myreturn='';
		if ($this->tpl->get_var_silent('widget.content')!='') {
			$widget['title']='Latest comments';	// translate this
			$widget['id']='latest_blog_comments';
			$this->tpl->set_file('temp','static/menu_widget.html');
			$this->tpl->set_var('widget',$widget);
			$myreturn=$this->tpl->process('temp','temp',TPL_OPTIONAL);
			$this->tpl->drop_var('temp');
			$this->tpl->drop_var('widget.content');
		}
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
		$this->config['module_name']='Latest Blog Comments';
	}
}

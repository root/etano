<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/widget/latest_members/latest_members.class.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_latest_members extends icontent_widget {
	var $module_code='latest_members';
	var $widget=array();

	function widget_latest_members() {
		$this->_init();
		if (func_num_args()==1) {
			$more_args=func_get_arg(0);
			$this->config=array_merge($this->config,$more_args);
		}
	}


	function display(&$tpl) {
		$this->tpl=$tpl;
		$this->_content();
		return $this->_finish_display();
	}


	function _content() {
		$this->tpl->set_file('widget.content','cache/widgets/latest_members/display.html');
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$widget['title']='Latest Members';	// translate this
		$widget['id']='latest-members';
		$widget['action']='<a class="content-link link-more" href="search.php?act=latest" title="More New Members">More New Members</a>';	// translate this
		$this->tpl->set_file('temp','static/content_widget.html');
		$this->tpl->set_var('widget',$widget);
		$myreturn=$this->tpl->process('','temp',TPL_OPTIONAL);
		$this->tpl->drop_var('temp');
		return $myreturn;
	}
}

<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/interfaces/icontent_widget.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

abstract class icontent_widget {
	var $config=array();
	var $module_code='';
	var $tpl=false;

	public function display(&$tpl) {
	}


	protected function _title() {
	}


	protected function _content() {
	}


	protected function _bottom() {
	}


	public function process() {
	}


	public function settings_display() {
		return '';
	}


	public function settings_process() {
	}


	protected function _init() {
	}
}

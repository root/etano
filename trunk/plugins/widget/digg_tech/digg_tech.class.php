<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/widget/digg_tech/digg_tech.class.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_digg_tech extends icontent_widget {
	var $module_code='digg_tech';

	function widget_digg_tech() {
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
		global $dbtable_prefix;
		$this->tpl->set_file('widget_content','widgets/digg_tech/display.html');
		$query="SELECT `feed_xml` FROM `{$dbtable_prefix}feed_cache` WHERE `module_code`='".$this->module_code."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			require_once _BASEPATH_.'/includes/classes/feed_reader.class.php';
			$fr=new feedReader();
			$fr->setRawXML(mysql_result($res,0,0));
			$ok=$fr->parseFeed();
			if ($ok) {
				$items=$fr->getFeedOutputData();
				$this->tpl->set_loop('items',array_slice($items['item'],0,$this->config['num_stories']));
				$this->tpl->process('widget_content','widget_content',TPL_LOOP | TPL_NOLOOP);
				$this->tpl->drop_loop('items');
			}
		}
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$this->tpl->set_file('temp','static/content_widget.html');
		$myreturn=$this->tpl->process('','temp',TPL_OPTIONAL);
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
		$this->config['module_name']='Latest Digg Stories';
		$this->config['num_stories']=5;
		$this->config['refresh_interval']=5;
	}
}

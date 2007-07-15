<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/osignal_feed/osignal_feed.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_osignal_feed extends icontent_widget {
	var $module_code='osignal_feed';

	function widget_osignal_feed() {
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


	function _content() {
		global $dbtable_prefix;
		$query="SELECT `feed_xml` FROM `{$dbtable_prefix}feed_cache` WHERE `module_code`='".$this->module_code."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res) && mysql_result($res,0,0)) {
			require_once _BASEPATH_.'/includes/classes/feed_reader.class.php';
			$fr=new feedReader();
			$fr->setRawXML(mysql_result($res,0,0));
			$ok=$fr->parseFeed();
			if ($ok) {
				$items=$fr->getFeedOutputData();
				for ($i=0;isset($items['item'][$i]);++$i) {
					$items['item'][$i]['content:encoded']=substr($items['item'][$i]['content:encoded'],0,$this->config['num_chars']);
				}
				$this->tpl->set_file('widget.content','widgets/osignal_feed/display.html');
				$this->tpl->set_loop('loop',array_slice($items['item'],0,$this->config['num_stories']));
				$this->tpl->process('widget.content','widget.content',TPL_LOOP);
				$this->tpl->drop_loop('loop');
			}
		}
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$myreturn='';
		if ($this->tpl->get_var_silent('widget.content')!='') {
			$widget['title']='Original Signal Tech Feed';	// translate this
			$widget['id']='os_tech_feed';
			if (isset($this->config['area'])) {
				if ($this->config['area']=='admin') {
					$this->tpl->set_file('temp','static/widget.html');
				} elseif ($this->config['area']=='front') {
					$this->tpl->set_file('temp','static/front_widget.html');
				} else {
					$this->tpl->set_file('temp','static/content_widget.html');
				}
			} else {
				$this->tpl->set_file('temp','static/content_widget.html');
			}
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
		$this->config['module_name']='Latest Original Signal Stories';
		$this->config['num_stories']=5;
		$this->config['refresh_interval']=5;
		$this->config['num_chars']=400;
	}
}

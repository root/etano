<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/widget/feed/feed.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_feed extends icontent_widget {
	var $module_code='widget_feed';

	function widget_feed() {
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
		$dbtable_prefix=$GLOBALS['dbtable_prefix'];
		$this->tpl->set_file('widget_content','widgets/feed/display.html');
		if (!empty($this->config['feed_url'])) {
			$query="SELECT `feed_xml` FROM `{$dbtable_prefix}feed_cache` WHERE `feed_url`='".$this->config['feed_url']."' AND `update_time`>=now()-INTERVAL ".$this->config['refresh_interval']." MINUTE";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			require_once _BASEPATH_.'/includes/classes/feed_reader.class.php';
			$fr=new feedReader();
			if (mysql_num_rows($res)) {
				$fr->setRawXML(mysql_result($res,0,0));
			} else {
				$ok=$fr->getFeed($this->config['feed_url']);
				if ($ok) {
					$query="REPLACE INTO `{$dbtable_prefix}feed_cache` SET `feed_url`='".$this->config['feed_url']."',`feed_xml`='".sanitize_and_format($fr->getRawXML(),TYPE_STRING,FORMAT_ADDSLASH)."',`update_time`=now()";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
			}
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
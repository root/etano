<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/site_news_feed/site_news_feed.class.php
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

class widget_site_news_feed extends icontent_widget {
	var $module_code='site_news_feed';

	function __construct() {
		require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/plugins/widget/site_news/site_news.class.php';
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
		if (is_file(_BASEPATH_.'/rss/site_news.xml')) {
			require_once _BASEPATH_.'/includes/classes/feed_reader.class.php';
			$fr=new feedReader();
			$fr->getFeed(_BASEPATH_.'/rss/site_news.xml',true);
			if ($fr->parseFeed()) {
				$items=$fr->getFeedOutputData();

				if (isset($_SESSION[_LICENSE_KEY_]['user']['prefs'])) {
					for ($i=0;isset($items['item'][$i]);++$i) {
						$items['item'][$i]['dc:date']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$items['item'][$i]['dc:date']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
					}
				}
				$this->tpl->set_file('widget.content','widgets/site_news_feed/display.html');
				$this->tpl->set_loop('loop',array_slice($items['item'],0,$this->config['total']));
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
			$widget['title']=$GLOBALS['_lang'][215];
			$widget['id']='site_news_feed';
			if (isset($this->config['area']) && $this->config['area']=='front') {
				$this->tpl->set_file('temp','static/front_widget.html');
			} else {
				$this->tpl->set_file('temp','static/content_widget.html');
			}
			$this->tpl->set_var('widget',$widget);
			$myreturn=$this->tpl->process('temp','temp',TPL_OPTIONAL);
			$this->tpl->drop_var('temp');
			$this->tpl->drop_var('widget');
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


	protected function _init() {
		$this->config['module_name']=$GLOBALS['_lang'][215];
		$this->config['total']=3;
	}
}

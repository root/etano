<?php
/******************************************************************************
newdsb
===============================================================================
File:                       plugins/widget/latest_photos/latest_photos.class.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

class widget_latest_photos extends icontent_widget {
	var $module_code='latest_photos';
	var $widget=array();

	function widget_latest_photos() {
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
		$dbtable_prefix=$GLOBALS['dbtable_prefix'];
		$query="SELECT `photo_id`,`photo`,`_user` as `user` FROM `{$dbtable_prefix}user_photos` WHERE `del`=0 AND `status`='".STAT_APPROVED."' ORDER BY `date_posted` DESC LIMIT ".$this->config['num_photos'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$loop=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$loop[]=$rsrow;
		}
		if (!empty($loop)) {
			$loop[0]['class']='first';
			$this->tpl->set_file('widget.content','widgets/latest_photos/display.html');
			$this->tpl->set_loop('loop',$loop);
			$this->tpl->process('widget.content','widget.content',TPL_LOOP | TPL_OPTLOOP);
			$this->tpl->drop_loop('loop');
		}
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$widget['title']='Newest Photos';	// translate this
		$widget['id']='newest-photos';
		$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_path'].'search.php?st=latest" title="More New Photos">More New Photos</a>';	// translate this
		if (isset($this->config['area']) && $this->config['area']=='front') {
			$this->tpl->set_file('temp','static/front_widget.html');
		} else {
			$this->tpl->set_file('temp','static/content_widget.html');
		}
		$this->tpl->set_var('widget',$widget);
		$myreturn=$this->tpl->process('','temp',TPL_OPTIONAL);
		$this->tpl->drop_var('temp');
		return $myreturn;
	}


	function _init() {
		$this->config['num_photos']=6;
	}
}

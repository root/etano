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
		global $dbtable_prefix;
		$query="SELECT a.`fk_user_id` FROM `{$dbtable_prefix}user_profiles` a WHERE a.`_photo`<>'' AND a.`del`=0 AND a.`status`='".STAT_APPROVED."' ORDER BY a.`date_added` DESC LIMIT 15";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$user_ids=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$user_ids[]=$rsrow['fk_user_id'];
		}
		if (!empty($user_ids)) {
			require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
			$user_cache=new user_cache(get_my_skin());
			$loop=$user_cache->get_cache_beta($user_ids,array(),'result_user','tpl');
			if (!empty($loop)) {
				$loop[0]['class']='first';
				$loop=array_slice($loop,0,$this->config['num_members']);
				$this->tpl->set_file('widget.content','widgets/latest_members/display.html');
				$this->tpl->set_loop('loop',$loop);
				$this->tpl->process('widget.content','widget.content',TPL_LOOP | TPL_OPTLOOP);
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
			$widget['title']='Newest Members';	// translate this
			$widget['id']='newest-members';
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_path'].'search.php?st=latest" title="More New Members">More New Members</a>';	// translate this
			if (isset($this->config['area']) && $this->config['area']=='front') {
				$this->tpl->set_file('temp','static/front_widget.html');
			} else {
				$this->tpl->set_file('temp','static/content_widget.html');
			}
			$this->tpl->set_var('widget',$widget);
			$myreturn=$this->tpl->process('','temp',TPL_OPTIONAL);
			$this->tpl->drop_var('temp');
		}
		return $myreturn;
	}


	function _init() {
		$this->config['num_members']=6;
	}
}

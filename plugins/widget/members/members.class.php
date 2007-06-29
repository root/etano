<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/members/members.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

// accepts 'mode','total','cols','area' as parameters

class widget_members extends icontent_widget {
	var $module_code='members';
	var $widget=array();

	function widget_members() {
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
		global $page_last_modified_time;
		switch ($this->config['mode']) {
			case 'new':
				$query="SELECT a.`fk_user_id`,UNIX_TIMESTAMP(a.`last_changed`) as `last_changed` FROM `{$dbtable_prefix}user_profiles` a WHERE a.`_photo`<>'' AND a.`del`=0 AND a.`status`='".STAT_APPROVED."' ORDER BY a.`date_added` DESC";
				break;

			case 'vote':
				$query="SELECT a.`fk_user_id`,UNIX_TIMESTAMP(b.`last_changed`) as `last_changed` FROM `{$dbtable_prefix}user_stats` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`stat`='vote_score' AND b.`_photo`<>'' AND b.`status`='".STAT_APPROVED."' AND b.`del`=0 ORDER BY a.`value` DESC";
				break;

			case 'views':
				$query="SELECT a.`fk_user_id`,UNIX_TIMESTAMP(b.`last_changed`) as `last_changed` FROM `{$dbtable_prefix}user_stats` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`stat`='total_views' AND b.`_photo`<>'' AND b.`status`='".STAT_APPROVED."' AND b.`del`=0 ORDER BY a.`value` DESC";
				break;

			case 'comm':
				$query="SELECT a.`fk_user_id`,UNIX_TIMESTAMP(b.`last_changed`) as `last_changed` FROM `{$dbtable_prefix}user_stats` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`stat`='total_comments' AND b.`_photo`<>'' AND b.`status`='".STAT_APPROVED."' AND b.`del`=0 ORDER BY a.`value` DESC";
				break;

		}
		$query.=" LIMIT 15";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$user_ids=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$user_ids[]=$rsrow['fk_user_id'];
			if ($page_last_modified_time<$rsrow['last_changed']) {
				$page_last_modified_time=$rsrow['last_changed'];
			}
		}
		if (!empty($user_ids)) {
			require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
			$user_cache=new user_cache(get_my_skin());
			$loop=$user_cache->get_cache_beta($user_ids,'result_user','tpl');
			unset($user_cache);
			if (!empty($loop)) {
				$loop[0]['class']='first';
				$loop=array_slice($loop,0,$this->config['total']);
				$this->tpl->set_file('widget.content','widgets/members/display.html');
				$this->tpl->set_loop('loop',$loop);
				$this->tpl->process('widget.content','widget.content',TPL_LOOP | TPL_OPTLOOP);
				$this->tpl->drop_loop('loop');
				unset($loop);
			}
		}
	}


	/*
	*	Used to wrap the content in the widget html code
	*/
	function _finish_display() {
		$myreturn='';
		if ($this->tpl->get_var_silent('widget.content')!='') {
			switch ($this->config['mode']) {
				case 'new':
					$widget['title']='Newest Members';	// translate this
					$widget['id']='widg_new_members';
					break;

				case 'vote':
					$widget['title']='Most Voted Members';	// translate this
					$widget['id']='widg_vote_members';
					break;

				case 'views':
					$widget['title']='Most Popular Members';	// translate this
					$widget['id']='widg_views_members';
					break;

				case 'comm':
					$widget['title']='Most Discussed Members';	// translate this
					$widget['id']='widg_comm_members';
					break;

			}
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_url'].'search.php?st='.$this->config['mode'].'" title="More Members">More Members</a>';	// translate this
			if (isset($this->config['area']) && $this->config['area']=='front') {
				$this->tpl->set_file('temp','static/front_widget.html');
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


	function _init() {
		$this->config['total']=6;
	}
}

<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/blogs/blogs.class.php
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

// accepts 'mode','total','area' as parameters

class widget_blogs extends icontent_widget {
	var $module_code='blogs';
	var $widget=array();

	function __construct() {
		require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
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


	protected function _content() {
		global $dbtable_prefix;
		global $page_last_modified_time;
		$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`last_changed`) as `last_changed` FROM `{$dbtable_prefix}blog_posts` a WHERE a.`is_public`=1 AND a.`status`=".STAT_APPROVED;
		switch ($this->config['mode']) {
			case 'new':
				$query.=" ORDER BY a.`date_posted` DESC";
				break;

			case 'views':
				$query.=" AND a.`stat_views`>0 ORDER BY a.`stat_views` DESC";
				break;

			case 'comm':
				$query.=" AND a.`stat_comments`>0 ORDER BY a.`stat_comments` DESC";
				break;

		}
		$query.=" LIMIT ".$this->config['total'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$post_ids=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$post_ids[]=$rsrow['post_id'];
			if ($page_last_modified_time<$rsrow['last_changed']) {
				$page_last_modified_time=$rsrow['last_changed'];
			}
		}
		if (!empty($post_ids)) {
			require_once _BASEPATH_.'/includes/classes/blog_posts_cache.class.php';
			$blog_posts_cache=new blog_posts_cache();
			$loop=$blog_posts_cache->get_tpl_array($post_ids);
			unset($blog_posts_cache);

			for ($i=0;isset($loop[$i]);++$i) {
				$loop[$i]['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$loop[$i]['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
				if (isset($GLOBALS['_list_of_online_members'][$loop[$i]['fk_user_id']])) {
					$loop[$i]['is_online']='is_online';
					$loop[$i]['user_online_status']=$GLOBALS['_lang'][102];
				} else {
					$loop[$i]['user_online_status']=$GLOBALS['_lang'][103];
				}
			}
			if (!empty($loop)) {
				$loop[0]['class']='first';
				$loop[count($loop)-1]['class']='last';
				$this->tpl->set_file('widget.content','widgets/blogs/display.html');
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
					$widget['title']=$GLOBALS['_lang'][104];
					$widget['id']='widg_new_blogs';
					break;

				case 'views':
					$widget['title']=$GLOBALS['_lang'][105];
					$widget['id']='widg_views_blogs';
					break;

				case 'comm':
					$widget['title']=$GLOBALS['_lang'][106];
					$widget['id']='widg_comm_blogs';
					break;

			}
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_url'].'blog_search.php?st='.$this->config['mode'].'" title="'.$GLOBALS['_lang'][205].'">'.$GLOBALS['_lang'][205].'</a>';
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


	protected function _init() {
		$this->config['total']=3;
		$this->config['mode']='new';
	}
}

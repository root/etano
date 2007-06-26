<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/blogs/blogs.class.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

// accepts 'mode','total','area' as parameters

class widget_blogs extends icontent_widget {
	var $module_code='blogs';
	var $widget=array();

	function widget_blogs() {
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
		$query="SELECT a.`post_id` FROM `{$dbtable_prefix}blog_posts` a WHERE a.`is_public`=1 AND a.`status`='".STAT_APPROVED."'";
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
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$post_ids[]=mysql_result($res,$i,0);
		}
		if (!empty($post_ids)) {
			require_once _BASEPATH_.'/includes/classes/blog_posts_cache.class.php';
			$blog_posts_cache=new blog_posts_cache();
			$loop=$blog_posts_cache->get_tpl_array($post_ids);
			unset($blog_posts_cache);

			for ($i=0;isset($loop[$i]);++$i) {
				$loop[$i]['date_posted']=strftime($_SESSION['user']['prefs']['datetime_format'],$loop[$i]['date_posted']+$_SESSION['user']['prefs']['time_offset']);
				if (isset($GLOBALS['_list_of_online_members'][$loop[$i]['fk_user_id']])) {
					$loop[$i]['is_online']='is_online';
					$loop[$i]['user_online_status']='is online';	// translate
				} else {
					$loop[$i]['user_online_status']='is offline';	// translate
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
					$widget['title']='Newest Posts';	// translate this
					$widget['id']='widg_new_blogs';
					break;

				case 'views':
					$widget['title']='Most Popular Posts';	// translate this
					$widget['id']='widg_views_blogs';
					break;

				case 'comm':
					$widget['title']='Most Discussed Posts';	// translate this
					$widget['id']='widg_comm_blogs';
					break;

			}
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_url'].'blog_search.php?st='.$this->config['mode'].'" title="More Blogs">More Blogs</a>';	// translate this
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
		$this->config['total']=3;
		$this->config['mode']='new';
	}
}

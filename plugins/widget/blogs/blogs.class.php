<?php
/******************************************************************************
newdsb
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

// accepts 'mode','total','area','chars' as parameters

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
		$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`is_public`=1 AND a.`status`='".STAT_APPROVED."'";
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
		$bbcode_blogs=get_site_option('bbcode_blogs','core_blog');
		$loop=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['date_posted']=strftime($GLOBALS['_user_settings']['datetime_format'],$rsrow['date_posted']+$GLOBALS['_user_settings']['time_offset']);
			$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
			if (isset($this->config['chars']) && !empty($this->config['chars'])) {
				$rsrow['post_content']=substr($rsrow['post_content'],0,strrpos(substr($rsrow['post_content'],0,$this->config['chars']),' '));
			}
			$rsrow['post_content']=sanitize_and_format($rsrow['post_content'],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
			if ($bbcode_blogs) {
				$rsrow['post_content']=bbcode2html($rsrow['post_content']);
			}
			if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
				$rsrow['photo']='no_photo.gif';
			}
			if (empty($rsrow['fk_user_id'])) {
				unset($rsrow['fk_user_id']);
			}
			$loop[]=$rsrow;
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
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_path'].'blog_search.php?st='.$this->config['mode'].'" title="More Blogs">More Blogs</a>';	// translate this
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
		$this->config['total']=3;
		$this->config['mode']='new';
	}
}

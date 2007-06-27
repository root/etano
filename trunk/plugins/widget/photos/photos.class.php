<?php
/******************************************************************************
Etano
===============================================================================
File:                       plugins/widget/photos/photos.class.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once _BASEPATH_.'/includes/interfaces/icontent_widget.class.php';

// accepts 'mode','total','cols','area','uid' as parameters

class widget_photos extends icontent_widget {
	var $module_code='photos';
	var $widget=array();

	function widget_photos() {
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
		$query="SELECT `photo_id`,`photo`,`_user` as `user` FROM `{$dbtable_prefix}user_photos` WHERE `is_private`=0 AND `status`='".STAT_APPROVED."' AND `del`=0";
		switch ($this->config['mode']) {
			case 'vote':
				$query.=" AND `stat_votes_total`>0 ORDER BY `stat_votes_total` DESC";
				break;

			case 'new':
				$query.=" ORDER BY `date_posted` DESC";
				break;

			case 'views':
				$query.=" AND `stat_views`>0 ORDER BY `stat_views` DESC";
				break;

			case 'comm':
				$query.=" AND `stat_comments`>0 ORDER BY `stat_comments` DESC";
				break;

			case 'user':
				if (!empty($this->config['uid'])) {
					$query.=" AND `fk_user_id`='".$this->config['uid']."' ORDER BY `date_posted` DESC";
				}
				break;

		}
		$query.=" LIMIT ".$this->config['total'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$loop=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			if (is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
				$loop[]=$rsrow;
			}
		}
		if (!empty($loop)) {
			if (isset($this->config['cols'])) {
				$total=(int)(count($loop)/$this->config['cols']);
				for ($i=1;$i<=$total;++$i) {
					$loop[$i*$this->config['cols']]['class']='first';
				}
			}
			$loop[0]['class']='first';
			$this->tpl->set_file('widget.content','widgets/photos/display.html');
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
					$widget['title']='Newest Photos';	// translate this
					$widget['id']='widg_new_photos';
					break;

				case 'vote':
					$widget['title']='Most Voted Photos';	// translate this
					$widget['id']='widg_vote_photos';
					break;

				case 'views':
					$widget['title']='Most Popular Photos';	// translate this
					$widget['id']='widg_views_photos';
					break;

				case 'comm':
					$widget['title']='Most Discussed Photos';	// translate this
					$widget['id']='widg_comm_photos';
					break;

				case 'user':
					$widget['title']='Photos';	// translate this
					$widget['id']='widg_user_photos';
					break;

			}
			$widget['action']='<a class="content-link link_more" href="'.$GLOBALS['tplvars']['relative_url'].'photo_search.php?st='.$this->config['mode'].'" title="More Photos">More</a>';	// translate this
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
		$this->config['mode']='new';
	}
}

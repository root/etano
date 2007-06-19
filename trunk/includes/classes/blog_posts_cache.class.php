<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/blog_posts_cache.class.php
$Revision: 76 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

class blog_posts_cache {
	var $cache_mode='disk';
	var $disk_path='';

	function blog_posts_cache() {
		if (defined(_CACHE_MODE_)) {
			$this->cache_mode=_CACHE_MODE_;
		}
		$this->disk_path=_CACHEPATH_.'/blogs/posts';
	}

	function get_post($post_id,$short=true) {
		$post=array();
		$post_id=(string)$post_id;
		if ($this->cache_mode=='disk') {
			if ($short) {
				$file=$this->disk_path.'/'.$post_id{0}.'/'.$post_id.'_short.inc.php';
			} else {
				$file=$this->disk_path.'/'.$post_id{0}.'/'.$post_id.'.inc.php';
			}
			if (is_file($file)) {
				include_once($file);
			} else {
				$post=false;
			}
		} elseif ($this->cache_mode=='db') {
			global $dbtable_prefix;
			$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`status`='".STAT_APPROVED."' AND a.`post_id`='$post_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$post=mysql_fetch_assoc($res);
				$post['title']=sanitize_and_format($post['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
				$post_content_short=substr($post['post_content'],0,strrpos(substr($post['post_content'],0,600),' '));
				$post_content_short=sanitize_and_format($post_content_short,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
				$post['post_content']=sanitize_and_format($post['post_content'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
				if (get_site_option('bbcode_blogs','core_blog')) {
					$post['post_content']=bbcode2html($post['post_content']);
					$post_content_short=bbcode2html($post_content_short);
				}
				if (empty($post['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$post['photo'])) {
					$post['photo']='no_photo.gif';
				} else {
					$post['has_photo']=true;
				}
				if (empty($post['fk_user_id'])) {
					unset($post['fk_user_id']);
				}
			}
		}
		return $post;
	}


	function get_tpl_array($post_ids,$short=true) {
		$myreturn=array();
		if (!is_array($post_ids)) {
			$post_ids=array($post_ids);
		}
		if ($this->cache_mode=='disk') {
			for ($id=0;isset($post_ids[$id]);++$id) {
				$post_ids[$id]=(string)$post_ids[$id];
				if ($short) {
					$file=$this->disk_path.'/'.$post_ids[$id]{0}.'/'.$post_ids[$id].'_short.inc.php';
				} else {
					$file=$this->disk_path.'/'.$post_ids[$id]{0}.'/'.$post_ids[$id].'.inc.php';
				}
				if (is_file($file)) {
					include $file;
					$myreturn[]=$post;
				}
			}
		} elseif ($this->cache_mode=='db') {
// BAD CODE! Must keep the order from $post_ids
			global $dbtable_prefix;
			$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`status`='".STAT_APPROVED."' AND a.`post_id` IN ('".join("','",$post_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($post=mysql_fetch_assoc($res)) {
				$post['title']=sanitize_and_format($post['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
				$post_content_short=substr($post['post_content'],0,strrpos(substr($post['post_content'],0,600),' '));
				$post_content_short=sanitize_and_format($post_content_short,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
				$post['post_content']=sanitize_and_format($post['post_content'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
				if (get_site_option('bbcode_blogs','core_blog')) {
					$post['post_content']=bbcode2html($post['post_content']);
					$post_content_short=bbcode2html($post_content_short);
				}
				if (empty($post['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$post['photo'])) {
					$post['photo']='no_photo.gif';
				} else {
					$post['has_photo']=true;
				}
				if (empty($post['fk_user_id'])) {
					unset($post['fk_user_id']);
				}
				$myreturn[]=$post;
			}
		}
		return $myreturn;
	}
}

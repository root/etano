<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/blog_posts_cache.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

class blog_posts_cache {
	var $disk_path='';

	function blog_posts_cache() {
		$this->disk_path=_CACHEPATH_.'/blogs/posts';
	}

	function get_post($post_id,$short=true) {
		$post=array();
		$post_id=(string)$post_id;
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
		return $post;
	}


	function get_tpl_array($post_ids,$short=true) {
		$myreturn=array();
		if (!is_array($post_ids)) {
			$post_ids=array($post_ids);
		}
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
		return $myreturn;
	}
}

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/blog_cache.class.php
$Revision: 76 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

class blog_cache {
	var $cache_mode='disk';
	var $disk_path='';
	var $skin='';

	function blog_cache($skin='basic') {
		if (defined(_CACHE_MODE_)) {
			$this->cache_mode=_CACHE_MODE_;
		}
		$this->skin=$skin;
		$this->disk_path=_BASEPATH_.'/skins_site/'.$skin.'/cache/blogs/';
	}

	function get_cache($post_id,$part) {
		$myreturn='';
		$post_id=(string)$post_id;
		if ($this->cache_mode=='disk') {
			$file=$this->disk_path.$post_id{0}.'/'.$post_id.'/'.$part.'.html';
			if (is_file($file)) {
				$fp=fopen($file,'rb');
				$myreturn=fread($fp,filesize($file));
				fclose($fp);
			}
		} elseif ($this->cache_mode=='db') {
			global $dbtable_prefix;
			$query="SELECT `cache` FROM `{$dbtable_prefix}blog_cache` WHERE `fk_post_id`='$post_id' AND `skin`='".$this->skin."' AND `part`='$part'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$myreturn=mysql_result($res,0,0);
			}
		}
		return $myreturn;
	}


	function get_cache_array($post_ids,$part) {
		$myreturn=array();
		if ($this->cache_mode=='disk') {
			for ($i=0;isset($post_ids[$i]);++$i) {
				$post_ids[$i]=(string)$post_ids[$i];
				$file=$this->disk_path.$post_ids[$i]{0}.'/'.$post_ids[$i].'/'.$part.'.html';
				if (is_file($file)) {
					$fp=fopen($file,'rb');
					$myreturn[]=fread($fp,filesize($file));
					fclose($fp);
				}
			}
		} elseif ($this->cache_mode=='db') {
// BAD CODE! Must keep the order from $post_ids
			global $dbtable_prefix;
			$query="SELECT `cache` FROM `{$dbtable_prefix}blog_cache` WHERE `fk_post_id` IN ('".join("','",$post_ids)."') AND `skin`='".$this->skin."' AND `part`='$part'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$myreturn[]=mysql_result($res,$i,0);
			}
		}
		return $myreturn;
	}


	/* more than 1 post_id makes sense only when $destination=='tpl'
	*	$destination='' (default) to return in array('part1'=>file1,'part2'=>file2) format
	*	$destination='tpl' to return in tpl format
	*/
	function get_cache_beta($post_ids,$parts,$destination='') {
		$myreturn='';
		if (!is_array($post_ids)) {
			$post_ids=array($post_ids);
		}
		if (!is_array($parts)) {
			$parts=array($parts);
		}
		if ($this->cache_mode=='disk') {
			for ($id=0;isset($post_ids[$id]);++$id) {
				$post_ids[$id]=(string)$post_ids[$id];
				for ($p=0;isset($parts[$p]);++$p) {
					$file=$this->disk_path.$post_ids[$id]{0}.'/'.$post_ids[$id].'/'.$parts[$p].'.html';
					if (is_file($file)) {
						$fp=fopen($file,'rb');
						if ($destination=='tpl') {
							$myreturn[$id][$parts[$p]]=fread($fp,filesize($file));
							$myreturn[$id]['pid']=$post_ids[$id];
							if ($id==0) {
								$myreturn[$id]['class']='first';
							}
						} else {
							$myreturn[$parts[$p]]=fread($fp,filesize($file));
						}
						fclose($fp);
					}
				}
			}
		} elseif ($this->cache_mode=='db') {
// BAD CODE! Must keep the order from $post_ids and the values in $myreturn must be continuous and not have gaps
			global $dbtable_prefix;
			$query="SELECT `fk_post_id`,`part`,`cache` FROM `{$dbtable_prefix}blog_cache` WHERE `fk_post_id` IN ('".join("','",$post_ids)."') AND `skin`='".$this->skin."' AND `part` IN ('".join("','",$part)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				if ($destination=='tpl') {
					$myreturn[$rsrow['fk_post_id']][$rsrow['part']]=$rsrow['cache'];
				} else {
					$myreturn[$rsrow['part']]=$rsrow['cache'];
				}
			}
		}
		return $myreturn;
	}
}

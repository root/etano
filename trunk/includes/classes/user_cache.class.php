<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/user_cache.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

class user_cache {
	var $cache_mode='disk';
	var $disk_path='';
	var $skin='';

	function user_cache($skin='basic') {
		if (defined(_CACHE_MODE_)) {
			$this->cache_mode=_CACHE_MODE_;
		}
		$this->skin=$skin;
		$this->disk_path=_BASEPATH_.'/skins/'.$skin.'/cache/users/';
	}

	function get_cache($user_id,$part) {
		$myreturn='';
		$user_id=(string)$user_id;
		if ($this->cache_mode=='disk') {
			$file=$this->disk_path.$user_id{0}.'/'.$user_id.'/'.$part.'.html';
			if (is_file($file)) {
				$fp=fopen($file,'rb');
				$myreturn=fread($fp,filesize($file));
				fclose($fp);
			}
		} elseif ($this->cache_mode=='db') {
			$dbtable_prefix=$GLOBALS['dbtable_prefix'];
			$query="SELECT `cache` FROM `{$dbtable_prefix}user_cache` WHERE `fk_user_id`='$user_id' AND `skin`='".$this->skin."' AND `part`='$part'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$myreturn=mysql_result($res,0,0);
			}
		}
		return $myreturn;
	}


	function get_cache_array($user_ids,$part) {
		$myreturn=array();
		if ($this->cache_mode=='disk') {
			for ($i=0;isset($user_ids[$i]);++$i) {
				$user_ids[$i]=(string)$user_ids[$i];
				$file=$this->disk_path.$user_ids[$i]{0}.'/'.$user_ids[$i].'/'.$part.'.html';
				if (is_file($file)) {
					$fp=fopen($file,'rb');
					$myreturn[]=fread($fp,filesize($file));
					fclose($fp);
				}
			}
		} elseif ($this->cache_mode=='db') {
// BAD CODE! Must keep the order from $user_ids
			$dbtable_prefix=$GLOBALS['dbtable_prefix'];
			$query="SELECT `cache` FROM `{$dbtable_prefix}user_cache` WHERE `fk_user_id` IN ('".join("','",$user_ids)."') AND `skin`='".$this->skin."' AND `part`='$part'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$myreturn[]=mysql_result($res,$i,0);
			}
		}
		return $myreturn;
	}


	/* more than 1 user_id makes sense only when $destination=='tpl'
	*	$destination='' default
	*	$destination='tpl' to return in tpl format
	*/
	function get_cache_beta($user_ids,$parts,$destination='') {
		$myreturn='';
		if (!is_array($user_ids)) {
			$user_ids=array($user_ids);
		}
		if (!is_array($parts)) {
			$parts=array($parts);
		}
		if ($this->cache_mode=='disk') {
			for ($u=0;isset($user_ids[$u]);++$u) {
				$user_ids[$u]=(string)$user_ids[$u];
				for ($p=0;isset($parts[$p]);++$p) {
					$file=$this->disk_path.$user_id[$u]{0}.'/'.$user_ids[$u].'/'.$parts[$p].'.html';
					if (is_file($file)) {
						$fp=fopen($file,'rb');
						if ($destination=='tpl') {
							$myreturn[$u][$part]=fread($fp,filesize($file));
						} else {
							$myreturn[$part]=fread($fp,filesize($file));
						}
						fclose($fp);
					}
				}
			}
		} elseif ($this->cache_mode=='db') {
// BAD CODE! Must keep the order from $user_ids and the values in $myreturn must be continuous and not have gaps
			$dbtable_prefix=$GLOBALS['dbtable_prefix'];
			$query="SELECT `fk_user_id`,`part`,`cache` FROM `{$dbtable_prefix}user_cache` WHERE `fk_user_id` IN ('".join("','",$user_ids)."') AND `skin`='".$this->skin."' AND `part` IN ('".join("','",$part)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				if ($destination=='tpl') {
					$myreturn[$rsrow['fk_user_id']][$rsrow['part']]=$rsrow['cache'];
				} else {
					$myreturn[$rsrow['part']]=$rsrow['cache'];
				}
			}
		}
		return $myreturn;
	}
}

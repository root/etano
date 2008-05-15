<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/classes/user_cache.class.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require _BASEPATH_.'/includes/classes/Cache/Lite.php';

class user_cache {
	var $skin='';
	var $cache=null;

	function user_cache() {
		$this->skin=get_my_skin();
		$this->cache=new Cache_Lite($GLOBALS['_cache_config']);
	}

	function get_categ($user_id,$pcat_id) {
		return $this->cache->get('skin'.$this->skin.$user_id.'pcat'.$pcat_id);
	}


	function get_cache_array($user_ids,$part,$inject_by_uid=array()) {
		global $page_last_modified_time;
		if (!isset($page_last_modified_time)) {
			$page_last_modified_time=0;
		}
		$myreturn=array();
		for ($i=0;!empty($user_ids[$i]);++$i) {
			$user_ids[$i]=(string)$user_ids[$i];
			$temp=$this->cache->get('skin'.$this->skin.$user_ids[$i].$part);
			if (!empty($temp)) {
				if (isset($inject_by_uid[$user_ids[$i]])) {
					$GLOBALS['tpl']->set_var('temp',$temp);
					$GLOBALS['tpl']->set_var('inject',$inject_by_uid[$user_ids[$i]]);
					$temp=$GLOBALS['tpl']->process('temp','temp');
				}
				$myreturn[]=$temp;
				if (isset($GLOBALS['_list_of_online_members'][(int)$user_ids[$i]]) && $page_last_modified_time<$GLOBALS['_list_of_online_members'][(int)$user_ids[$i]]) {
					$page_last_modified_time=$GLOBALS['_list_of_online_members'][(int)$user_ids[$i]];
				}
			}
		}
		return $myreturn;
	}


	function get_cache_tpl($user_ids,$part) {
		global $page_last_modified_time;
		if (!isset($page_last_modified_time)) {
			$page_last_modified_time=0;
		}
		$myreturn='';
		if (!is_array($user_ids)) {
			$user_ids=array($user_ids);
		}
		for ($id=0;isset($user_ids[$id]);++$id) {
			$temp=$this->cache->get('skin'.$this->skin.$user_ids[$id].$part);
			if (!empty($temp)) {
				$myreturn[$id][$part]=$temp;
				$myreturn[$id]['uid']=$user_ids[$id];
				if (isset($GLOBALS['_list_of_online_members'][(int)$user_ids[$id]])) {
					$myreturn[$id]['is_online']='is_online';
					$myreturn[$id]['user_online_status']=$GLOBALS['_lang'][102];
					if ($page_last_modified_time<$GLOBALS['_list_of_online_members'][(int)$user_ids[$id]]) {
						$page_last_modified_time=$GLOBALS['_list_of_online_members'][(int)$user_ids[$id]];
					}
				} else {
					$myreturn[$id]['is_online']='is_offline';
					$myreturn[$id]['user_online_status']=$GLOBALS['_lang'][103];
				}
			}
		}
		return $myreturn;
	}
}

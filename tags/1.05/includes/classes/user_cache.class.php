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

class user_cache {
	var $disk_path='';
	var $skin='';

	function user_cache($skin='basic') {
		$this->skin=$skin;
		$this->disk_path=_BASEPATH_.'/skins_site/'.$skin.'/cache/users/';
	}

	function get_cache($user_id,$part) {
		global $page_last_modified_time;
		if (!isset($page_last_modified_time)) {
			$page_last_modified_time=0;
		}
		$myreturn='';
		$user_id=(string)$user_id;
		$file=$this->disk_path.$user_id{0}.'/'.$user_id.'/'.$part.'.html';
		if (is_file($file)) {
			$myreturn=file_get_contents($file);
		}
		if (isset($GLOBALS['_list_of_online_members'][(int)$user_id]) && $page_last_modified_time<$GLOBALS['_list_of_online_members'][(int)$user_id]) {
			$page_last_modified_time=$GLOBALS['_list_of_online_members'][(int)$user_id];
		}
		return $myreturn;
	}


	function get_cache_array($user_ids,$part,$inject_by_uid=array()) {
		global $page_last_modified_time;
		if (!isset($page_last_modified_time)) {
			$page_last_modified_time=0;
		}
		$myreturn=array();
		for ($i=0;!empty($user_ids[$i]);++$i) {
			$user_ids[$i]=(string)$user_ids[$i];
			$file=$this->disk_path.$user_ids[$i]{0}.'/'.$user_ids[$i].'/'.$part.'.html';
			if (is_file($file)) {
				if (isset($inject_by_uid[$user_ids[$i]])) {
					$temp=file_get_contents($file);
					$GLOBALS['tpl']->set_var('temp',$temp);
					$GLOBALS['tpl']->set_var('inject',$inject_by_uid[$user_ids[$i]]);
					$temp=$GLOBALS['tpl']->process('temp','temp');
					$myreturn[]=$temp;
				} else {
					$myreturn[]=file_get_contents($file);
				}
				if (isset($GLOBALS['_list_of_online_members'][(int)$user_ids[$i]]) && $page_last_modified_time<$GLOBALS['_list_of_online_members'][(int)$user_ids[$i]]) {
					$page_last_modified_time=$GLOBALS['_list_of_online_members'][(int)$user_ids[$i]];
				}
			}
		}
		return $myreturn;
	}


	/* more than 1 user_id makes sense only when $destination=='tpl'
	*	$destination='' (default) to return in array('part1'=>file1,'part2'=>file2) format
	*	$destination='tpl' to return in tpl format
	*/
	function get_cache_beta($user_ids,$parts,$destination='') {
		global $page_last_modified_time;
		if (!isset($page_last_modified_time)) {
			$page_last_modified_time=0;
		}
		$myreturn='';
		if (!is_array($user_ids)) {
			$user_ids=array($user_ids);
		}
		if (!is_array($parts)) {
			$parts=array($parts);
		}
		for ($id=0;isset($user_ids[$id]);++$id) {
			for ($p=0;isset($parts[$p]);++$p) {
				$file=$this->disk_path.(string)$user_ids[$id]{0}.'/'.$user_ids[$id].'/'.$parts[$p].'.html';
				if (is_file($file)) {
					if ($destination=='tpl') {
						$myreturn[$id][$parts[$p]]=file_get_contents($file);
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
					} else {
						$myreturn[$parts[$p]]=file_get_contents($file);
					}
				}
			}
		}
		return $myreturn;
	}
}

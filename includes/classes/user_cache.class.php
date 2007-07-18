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
		$myreturn='';
		$user_id=(string)$user_id;
		$file=$this->disk_path.$user_id{0}.'/'.$user_id.'/'.$part.'.html';
		if (is_file($file)) {
			$fp=fopen($file,'rb');
			$myreturn=fread($fp,filesize($file));
			fclose($fp);
		}
		return $myreturn;
	}


	function get_cache_array($user_ids,$part) {
		$myreturn=array();
		for ($i=0;!empty($user_ids[$i]);++$i) {
			$user_ids[$i]=(string)$user_ids[$i];
			$file=$this->disk_path.$user_ids[$i]{0}.'/'.$user_ids[$i].'/'.$part.'.html';
			if (is_file($file)) {
				$fp=fopen($file,'rb');
				$myreturn[]=fread($fp,filesize($file));
				fclose($fp);
			}
		}
		return $myreturn;
	}


	/* more than 1 user_id makes sense only when $destination=='tpl'
	*	$destination='' (default) to return in array('part1'=>file1,'part2'=>file2) format
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
		for ($id=0;isset($user_ids[$id]);++$id) {
			for ($p=0;isset($parts[$p]);++$p) {
				$file=$this->disk_path.(string)$user_ids[$id]{0}.'/'.$user_ids[$id].'/'.$parts[$p].'.html';
				if (is_file($file)) {
					$fp=fopen($file,'rb');
					if ($destination=='tpl') {
						$myreturn[$id][$parts[$p]]=fread($fp,filesize($file));
						$myreturn[$id]['uid']=$user_ids[$id];
						if (isset($GLOBALS['_list_of_online_members'][(int)$user_ids[$id]])) {
							$myreturn[$id]['is_online']='is_online';
							$myreturn[$id]['user_online_status']='is online';	// translate
						} else {
							$myreturn[$id]['is_online']='is_offline';
							$myreturn[$id]['user_online_status']='is offline';	// translate
						}
					} else {
						$myreturn[$parts[$p]]=fread($fp,filesize($file));
					}
					fclose($fp);
				}
			}
		}
		return $myreturn;
	}
}

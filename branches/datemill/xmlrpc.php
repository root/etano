<?php

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/classes/IXR_Library.class.php';

$server = new IXR_Server(array('pingback.ping'=>'pingback_ping'));

function pingback_ping($args) {
	global $dbtable_prefix;
    $myreturn=0;
    $other_page=$args[0];
    $my_page=$args[1];
	if ($my_page!=$other_page) {
		$query="SELECT `ping_id` FROM `pingbacks` WHERE `other_page`='".md5($other_page)."' AND `my_page`='".md5($my_page)."' LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=48; //The pingback has already been registered.
		} else {
			$where='';
			$ping_to='';
			if (preg_match('/blog_post_view\.php\?pid=(\d+)?/',$my_page,$m)) {
				$where.='`post_id`='.$m[1];
				$ping_to='id';
			} elseif (preg_match('/\/blogpost\/(\d+)\//',$my_page,$m)) {
				$where.="`post_id`=".$m[1];
				$ping_to='alt';
			} else {
				$myreturn=33;	// The specified target URI cannot be used as a target.
			}
			if (empty($myreturn)) {
				$query="SELECT `post_id`,`alt_url` FROM `{$dbtable_prefix}blog_posts` WHERE $where";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$rsrow=mysql_fetch_assoc($res);
					if ($ping_to=='alt' && $my_page!=$rsrow['alt_url']) {
						$myreturn=32;	//The specified target URI does not exist.
					}
				} else {
					$myreturn=32;	//The specified target URI does not exist.
				}
			}
			if (empty($myreturn)) {	// everything's fine on our side, let's see check the calling page
				$ch=curl_init($other_page);
				curl_setopt($ch,CURLOPT_HEADER, true);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				$return=curl_exec($ch);
				if (curl_errno($ch) || strpos($return,'HTTP/1.1 200 OK')!==0) {
					$myreturn=16;	//The source URI does not exist.
				}
				$temp=str_replace(array('/','.','-'),array('\/','\.','\-'),$my_page);	// for regexp
				if (preg_match('/href=(\'|")'.$temp.'(\'|")/',$return,$m)) {
					$query="INSERT INTO `pingback` SET `other_page`='".md5($other_page)."',`my_page`='".md5($my_page)."'";
	//				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				} else {
					$myreturn=$temp;	//The source URI does not contain a link to the target URI, and so cannot be used as a source.
				}
			}
		}
	}
    return $myreturn;
}


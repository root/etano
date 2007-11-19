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
			$post_id=0;
			$where='';
			$ping_to='';
			if (preg_match('/blog_post_view\.php\?pid=(\d+)?/',$my_page,$m)) {
				$post_id=$m[1];
				$where.='`post_id`='.$m[1];
				$ping_to='id';
			} elseif (preg_match('/\/blogpost\/(\d+)\//',$my_page,$m)) {
				$post_id=$m[1];
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
				$other_page_content=curl_exec($ch);
				if (curl_errno($ch) || strpos($other_page_content,'HTTP/1.1 200 OK')!==0) {
					$myreturn=16;	//The source URI does not exist.
				}
				$temp=str_replace(array('/','.','-'),array('\/','\.','\-'),$my_page);	// for regexp
				if (preg_match('/(.{0,100})href=[\'"]?'.$temp.'[\'"]?(.{0,100})/',$other_page_content,$m)) {
					$query="INSERT INTO `pingbacks` SET `other_page`='".md5($other_page)."',`my_page`='".md5($my_page)."'";
//					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$m[1]=trim(preg_replace(array('/.*>/','/<.*/'),'',strip_tags($m[1])));
					$m[2]=trim(preg_replace(array('/.*>/','/<.*/'),'',strip_tags($m[2])));
					$other_page=sanitize_and_format($other_page,TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD]);
					$user='';
					if (preg_match('/<title>(.+)<\/title>/',$other_page_content,$x)) {
						$user=$x[1];
					} else {
						$user=substr($other_page,7,strpos($other_page,'/',8));
					}
					if (strlen($user)>200) {
						$user=substr($user,0,197).'...';
					}
					$query="INSERT INTO `{$dbtable_prefix}blog_comments` SET `fk_parent_id`=$post_id,`_user`='$user',`website`='$other_page',`comment`='[...] ".$m[1].' '.$m[2]." [...]',`date_posted`='".gmdate('YmdHis')."',`status`=".STAT_APPROVED.",`processed`=1";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					return 'ok';
				} else {
					$myreturn=17;	//The source URI does not contain a link to the target URI, and so cannot be used as a source.
				}
			}
		}
	}
    return $myreturn;
}


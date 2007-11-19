<?
$jobs[]='gen_blogposts_cache';

function gen_blogposts_cache() {
	global $dbtable_prefix;
	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$short_blog_chars=1000;
	$config=get_site_option(array('bbcode_blogs','use_smilies'),'core_blog');

	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	$fileop=new fileop();

	$post_ids=array();
	$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo`,c.`blog_name`,a.`alt_url`,c.`alt_url` as `blog_url` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b,`{$dbtable_prefix}user_blogs` c WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`fk_blog_id`=c.`blog_id` AND a.`status`=".STAT_APPROVED." AND a.`last_changed`>=DATE_SUB('".gmdate('YmdHis')."',INTERVAL ".($interval+2)." MINUTE)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($blog=mysql_fetch_assoc($res)) {
		$post_ids[]=$blog['post_id'];
		$blog['title']=sanitize_and_format($blog['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
		$post_content_short=substr($blog['post_content'],0,strrpos(substr($blog['post_content'],0,$short_blog_chars),' '));
		$post_content_short=sanitize_and_format($post_content_short,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
		$blog['post_content']=sanitize_and_format($blog['post_content'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
		if (!empty($config['bbcode_blogs'])) {
			$blog['post_content']=bbcode2html($blog['post_content']);
			$post_content_short=bbcode2html($post_content_short);
		}
		if (!empty($config['use_smilies'])) {
			$blog['post_content']=text2smilies($blog['post_content']);
			$post_content_short=text2smilies($post_content_short);
		}
		if (empty($blog['fk_user_id'])) {
			unset($blog['fk_user_id']);
		}

		$towrite='<?php $post='.var_export($blog,true).';';
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'.inc.php',$towrite);

		$blog['post_content']=$post_content_short;
		$towrite='<?php $post='.var_export($blog,true).';';
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'_short.inc.php',$towrite);
	}

	if (!empty($post_ids)) {
		pingback_ping($post_ids);
	}
	return true;
}


function pingback_ping($post_ids) {
	for ($i=0;isset($post_ids[$i]);++$i) {
		if (is_file(_CACHEPATH_.'/blogs/posts/'.$post_ids[$i]{0}.'/'.$post_ids[$i].'.inc.php')) {
			include_once _CACHEPATH_.'/blogs/posts/'.$post_ids[$i]{0}.'/'.$post_ids[$i].'.inc.php';
			if (!empty($post['alt_url'])) {
				$my_page=$post['alt_url'];
			} else {
				$my_page=_BASEURL_.'/blog_post_view.php?pid='.$post['post_id'];
			}
			if (preg_match_all('/href="(.+?)"/',$post['post_content'],$m)) {
				$ch=curl_init();
				curl_setopt($ch,CURLOPT_HEADER,true);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_NOBODY,true);
				for ($j=0;isset($m[1][$j]);++$j) {
					$other_page=$m[1][$j];
					curl_setopt($ch,CURLOPT_URL,$other_page);
					$other_page_response=curl_exec($ch);
					if (!curl_errno($ch) && strpos($other_page_response,'HTTP/1.1 200 OK')===0) {
						if (preg_match('/X-Pingback: ([^\r\n]+)\r?\n/m',$other_page_response,$x)) {
							$rpc_url=$x[1];
							require_once _BASEPATH_.'/includes/classes/IXR_Library.class.php';
							$client=new IXR_Client($rpc_url);
							$client->query('pingback.ping',$my_page,$other_page);
						}
					}
				}
			}
		}
	}
}

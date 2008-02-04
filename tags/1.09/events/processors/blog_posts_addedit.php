<?php

$_on_after_approve[]='on_after_approve_blog_post';

// the calling script MUST HAVE $post_ids array as GLOBALS
function on_after_approve_blog_post() {
	global $dbtable_prefix,$post_ids;
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	$fileop=new fileop();
	$query="SELECT `post_id`,`fk_blog_id`,`fk_user_id` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id` IN ('".join("','",$post_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$post_ids=array();	// yup
	$blog_ids=array();
	$user_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$post_ids[]=$rsrow['post_id'];	// get only the not processed ones
		if (!isset($blog_ids[$rsrow['fk_blog_id']])) {
			$blog_ids[$rsrow['fk_blog_id']]=1;
		} else {
			++$blog_ids[$rsrow['fk_blog_id']];
		}
		if (!isset($user_ids[$rsrow['fk_user_id']])) {
			$user_ids[$rsrow['fk_user_id']]=1;
		} else {
			++$user_ids[$rsrow['fk_user_id']];
		}
	}

	$year=(int)date('Y');
	$month=(int)date('m');
	foreach ($blog_ids as $bid=>$num) {
		// blog stats
		$bid=(string)$bid;
		$query="UPDATE `{$dbtable_prefix}user_blogs` SET `stat_posts`=`stat_posts`+$num WHERE `blog_id`=$bid";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		// blog_archive
		$blog_archive=array();
		if (is_file(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php')) {
			include _CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php';
		}
		if (isset($blog_archive[$year][$month])) {
			$blog_archive[$year][$month]+=$num;
		} else {
			$blog_archive[$year][$month]=$num;
		}
		krsort($blog_archive,SORT_NUMERIC);
		$towrite='<?php $blog_archive='.var_export($blog_archive,true).';';
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php',$towrite);
	}

	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'blog_posts',$num);
		add_member_score($uid,'add_blog',$num);
	}
	if (!empty($post_ids)) {
		$query="UPDATE `{$dbtable_prefix}blog_posts` SET `processed`=1 WHERE `post_id` IN ('".join("','",$post_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

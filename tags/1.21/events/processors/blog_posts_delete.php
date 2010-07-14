<?php

$_on_before_delete[]='on_before_delete_blog_post';

// the calling script MUST HAVE $post_ids array as GLOBALS
function on_before_delete_blog_post() {
	global $dbtable_prefix,$post_ids;
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	$fileop=new fileop();
	$query="SELECT `post_id`,`fk_blog_id`,`fk_user_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id` IN ('".join("','",$post_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$blog_ids=array();
	$user_ids=array();
	$dates=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($blog_ids[$rsrow['fk_blog_id']])) {
			--$blog_ids[$rsrow['fk_blog_id']];
		} else {
			$blog_ids[$rsrow['fk_blog_id']]=-1;
		}
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			--$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=-1;
		}
		$dates[$rsrow['fk_blog_id']][]=$rsrow['date_posted'];
	}

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
		for ($i=0;isset($dates[$bid][$i]);++$i) {
			$year=(int)date('Y',$dates[$bid][$i]);
			$month=(int)date('m',$dates[$bid][$i]);
			if (isset($blog_archive[$year][$month])) {
				--$blog_archive[$year][$month];
			}
			if (empty($blog_archive[$year][$month])) {
				unset($blog_archive[$year][$month]);
			}
		}
		krsort($blog_archive,SORT_NUMERIC);
		$towrite='<?php $blog_archive='.var_export($blog_archive,true).';';
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php',$towrite);
	}

	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'blog_posts',$num);
		add_member_score($uid,'del_blog',-$num);	// -$num because $num is already negative.
	}
}

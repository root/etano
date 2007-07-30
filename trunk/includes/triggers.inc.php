<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/triggers.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

function on_approve_comment($comment_ids,$type) {
	global $dbtable_prefix;
	switch ($type) {

		case 'blog':
			$table="`{$dbtable_prefix}blog_comments`";
			$parent_table="`{$dbtable_prefix}blog_posts`";
			$parent_key="`post_id`";
			break;

	 	case 'photo':
			$table="`{$dbtable_prefix}photo_comments`";
			$parent_table="`{$dbtable_prefix}user_photos`";
			$parent_key="`photo_id`";
			break;

		case 'user':
			$table="`{$dbtable_prefix}profile_comments`";
			$parent_table="`{$dbtable_prefix}user_profiles`";
			$parent_key="`fk_user_id`";
			break;

	}

	$query="SELECT a.`comment_id`,a.`fk_parent_id`,a.`fk_user_id`,b.`fk_user_id` as `fk_parent_owner_id` FROM $table a,$parent_table b WHERE a.`comment_id` IN ('".join("','",$comment_ids)."') AND a.`fk_parent_id`=b.$parent_key AND a.`processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$comment_ids=array();	// yup
	$parent_ids=array();
	$user_ids=array();
	$parent_owner_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$comment_ids[]=$rsrow['comment_id'];	// get only the not processed ones
		if (isset($parent_ids[$rsrow['fk_parent_id']])) {
			++$parent_ids[$rsrow['fk_parent_id']];
		} else {
			$parent_ids[$rsrow['fk_parent_id']]=1;
		}
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			++$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=1;
		}
		if (isset($parent_owner_ids[$rsrow['fk_parent_owner_id']])) {
			++$parent_owner_ids[$rsrow['fk_parent_owner_id']];
		} else {
			$parent_owner_ids[$rsrow['fk_parent_owner_id']]=1;
		}
	}
	if ($type!='user') {
		foreach ($parent_ids as $pid=>$num) {
			$query="UPDATE $parent_table SET `stat_comments`=`stat_comments`+$num WHERE $parent_key='$pid'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	} else {
		foreach ($parent_ids as $pid=>$num) {
			update_stats($pid,'profile_comments',$num);
		}
	}
	foreach ($parent_owner_ids as $uid=>$num) {
		if (!empty($uid) && (empty($_SESSION['user']['user_id']) || $_SESSION['user']['user_id']!=$uid)) {
			add_member_score($uid,'received_comment',$num);
		}
	}
	$query="UPDATE $table SET `processed`=1 WHERE `comment_id` IN ('".join("','",$comment_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function on_approve_photo($photo_ids) {
	global $dbtable_prefix;
	$query="SELECT `photo_id`,`fk_user_id`,`is_main`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$photo_ids=array();	// yup
	$user_ids=array();
	$scores=array();
	$score_photo=add_member_score(0,'add_photo',1,true);
	$score_main_photo=add_member_score(0,'add_main_photo',1,true);
	$main_photos=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$photo_ids[]=$rsrow['photo_id'];	// get only the not processed ones
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			++$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=1;
		}
		if (isset($scores[$rsrow['fk_user_id']])) {
			$scores[$rsrow['fk_user_id']]+=empty($rsrow['is_main']) ? $score_photo : $score_main_photo;
		} else {
			$scores[$rsrow['fk_user_id']]=empty($rsrow['is_main']) ? $score_photo : $score_main_photo;
		}
		if (!empty($rsrow['is_main'])) {
			$main_photos[$rsrow['fk_user_id']]=$rsrow['photo'];
		}
	}
	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'total_photos',$num);
	}
	foreach ($scores as $uid=>$score) {
		add_member_score($uid,'force',1,false,$score);
	}
	$now=gmdate('YmdHis');
	foreach ($main_photos as $uid=>$photo) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='$photo',`last_changed`='$now' WHERE `fk_user_id`=$uid";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	$query="UPDATE `{$dbtable_prefix}user_photos` SET `processed`=1 WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function on_approve_blog_post($post_ids) {
	global $dbtable_prefix;
	require_once 'classes/fileop.class.php';
	$fileop=new fileop();
	$query="SELECT `post_id`,`fk_blog_id`,`fk_user_id` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id` IN ('".join("','",$post_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$post_ids=array();	// yup
	$blog_ids=array();
	$user_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$post_ids[]=$rsrow['post_id'];	// get only the not processed ones
		if (isset($blog_ids[$rsrow['fk_blog_id']])) {
			++$blog_ids[$rsrow['fk_blog_id']];
		} else {
			$blog_ids[$rsrow['fk_blog_id']]=1;
		}
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			++$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=1;
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
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php','<?php $blog_archive='.var_export($blog_archive,true).';');
	}

	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'blog_posts',$num);
		add_member_score($uid,'add_blog',$num);
	}
	$query="UPDATE `{$dbtable_prefix}blog_posts` SET `processed`=1 WHERE `post_id` IN ('".join("','",$post_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function on_delete_blog_post($post_ids) {
	global $dbtable_prefix;
	require_once 'classes/fileop.class.php';
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
		$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php','<?php $blog_archive='.var_export($blog_archive,true).';');
	}

	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'blog_posts',$num);
		add_member_score($uid,'del_blog',-$num);	// -$num because $num is already negative.
	}
}

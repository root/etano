<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/triggers.inc.php
$Revision: 75 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
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
			break;

	}

	$query="SELECT `comment_id`,`fk_parent_id`,`fk_user_id` FROM $table WHERE `comment_id` IN ('".join("','",$comment_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$comment_ids=array();	// yup
	$parent_ids=array();
	$user_ids=array();
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
	foreach ($user_ids as $uid=>$num) {
		if (!empty($uid)) {
			update_stats($uid,'comments_made',$num);
		}
	}
	$query="UPDATE $table SET `processed`=1 WHERE `comment_id` IN ('".join("','",$comment_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function on_approve_photo($photo_ids) {
	global $dbtable_prefix;
	$query="SELECT `photo_id`,`fk_user_id` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$photo_ids=array();	// yup
	$user_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$photo_ids[]=$rsrow['photo_id'];	// get only the not processed ones
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			++$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=1;
		}
	}
	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'total_photos',$num);
	}
	$query="UPDATE `{$dbtable_prefix}user_photos` SET `processed`=1 WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function on_approve_blog_post($post_ids) {
	global $dbtable_prefix;
	require_once 'classes/modman.class.php';
	$modman=new modman();
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
		$query="UPDATE `{$dbtable_prefix}user_blogs` SET `stat_posts`=`stat_posts`+$num WHERE `blog_id`='$bid'";
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
		$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php','<?php $blog_archive='.var_export($blog_archive,true).';');
	}

	foreach ($user_ids as $uid=>$num) {
		update_stats($uid,'blog_posts',$num);
	}
	$query="UPDATE `{$dbtable_prefix}blog_posts` SET `processed`=1 WHERE `post_id` IN ('".join("','",$post_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}

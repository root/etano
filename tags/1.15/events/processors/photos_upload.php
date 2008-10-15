<?php

$_on_after_approve[]='on_after_approve_photo';

// the calling script MUST HAVE $comment_ids array and $comment_type as GLOBALS
function on_after_approve_photo() {
	global $dbtable_prefix,$photo_ids,$do_stats;
	$query="SELECT `photo_id`,`fk_user_id`,`is_main`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."') AND `processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$photo_ids=array();	// yup
	$user_ids=array();
	$scores=array();
	$score_photo=add_member_score(0,'add_photo',1,true);	// just read the score, don't set anything
	$score_main_photo=add_member_score(0,'add_main_photo',1,true);	// just read the score, don't set anything
	$main_photos=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$photo_ids[]=$rsrow['photo_id'];	// get only the not processed ones
		if ($do_stats) {
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
		}
		if (!empty($rsrow['is_main'])) {
			$main_photos[$rsrow['fk_user_id']]=$rsrow['photo'];
		}
	}
	if ($do_stats) {
		foreach ($user_ids as $uid=>$num) {
			update_stats($uid,'total_photos',$num);
		}
		foreach ($scores as $uid=>$score) {
			add_member_score($uid,'force',1,false,$score);
		}
	}
	$now=gmdate('YmdHis');
	foreach ($main_photos as $uid=>$photo) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='$photo',`last_changed`='$now' WHERE `fk_user_id`=$uid";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	// this is needed to recreate caches containing the new photo
	if (!empty($main_photos)) {
		$query="UPDATE `{$dbtable_prefix}blog_posts` SET `last_changed`='$now' WHERE `fk_user_id` IN (".join(',',array_keys($main_photos)).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}comments_blog` SET `last_changed`='$now' WHERE `fk_user_id` IN (".join(',',array_keys($main_photos)).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}comments_photo` SET `last_changed`='$now' WHERE `fk_user_id` IN (".join(',',array_keys($main_photos)).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}comments_profile` SET `last_changed`='$now' WHERE `fk_user_id` IN (".join(',',array_keys($main_photos)).")";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	if (!empty($photo_ids)) {
		$query="UPDATE `{$dbtable_prefix}user_photos` SET `processed`=1 WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

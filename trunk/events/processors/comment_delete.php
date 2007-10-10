<?php

$_on_before_delete[]='on_before_delete_comment';

// the calling script MUST HAVE $comment_ids array and $comment_type as GLOBALS
function on_before_delete_comment() {
	global $dbtable_prefix,$comment_ids,$comment_type;
	switch ($comment_type) {

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

	$query="SELECT a.`comment_id`,a.`fk_parent_id`,a.`fk_user_id`,b.`fk_user_id` as `fk_parent_owner_id` FROM $table a,$parent_table b WHERE a.`comment_id` IN ('".join("','",$comment_ids)."') AND a.`fk_parent_id`=b.$parent_key";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$parent_ids=array();
	$user_ids=array();
	$parent_owner_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($parent_ids[$rsrow['fk_parent_id']])) {
			--$parent_ids[$rsrow['fk_parent_id']];
		} else {
			$parent_ids[$rsrow['fk_parent_id']]=-1;
		}
		if (isset($user_ids[$rsrow['fk_user_id']])) {
			--$user_ids[$rsrow['fk_user_id']];
		} else {
			$user_ids[$rsrow['fk_user_id']]=-1;
		}
		if ($rsrow['fk_parent_owner_id']!=$rsrow['fk_user_id']) {
			if (isset($parent_owner_ids[$rsrow['fk_parent_owner_id']])) {
				--$parent_owner_ids[$rsrow['fk_parent_owner_id']];
			} else {
				$parent_owner_ids[$rsrow['fk_parent_owner_id']]=-1;
			}
		}
	}
	if ($comment_type!='user') {
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
		if (!empty($uid)) {
			add_member_score($uid,'removed_comment',-$num);	// -$num because $num is already negative.
		}
	}
}

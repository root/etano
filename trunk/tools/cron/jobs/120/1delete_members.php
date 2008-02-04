<?php
$jobs[]='delete_members';

function delete_members() {
	global $dbtable_prefix;

/* del=1 removes the user and all generated content
   del=2 removes the user but keeps the generated content */

	$query="SELECT `del`,`fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `del`<>0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$uids[1]=array();
	$uids[2]=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$uids[$rsrow['del']][]=$rsrow['fk_user_id'];
	}

	$all_uids=array_merge($uids[1],$uids[2]);

	// actions to do for all deleted members
	if (!empty($all_uids)) {
		$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}payments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}profile_comments` WHERE `fk_parent_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}queue_message` WHERE `fk_user_id` IN ('".join("','",$all_uids)."') OR `fk_user_id_other` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_mtpls` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id` IN ('".join("','",$all_uids)."') OR `fk_user_id_other` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `fk_user_id` IN ('".join("','",$all_uids)."') OR `fk_user_id_other` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_searches` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_settings2` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `fk_user_id` IN ('".join("','",$all_uids)."') OR `fk_user_id_other` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_stats` WHERE `fk_user_id` IN ('".join("','",$all_uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	// actions to do for members to be totally removed
	if (!empty($uids[1])) {
		$query="SELECT `blog_id` FROM `{$dbtable_prefix}user_blogs` WHERE `fk_user_id` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$blog_ids=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$blog_ids[]=mysql_result($res,$i,0);
		}
		if (!empty($blog_ids)) {
			$query="SELECT `post_id` FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id` IN ('".join("','",$blog_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$post_ids=array();
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$post_ids[]=mysql_result($res,$i,0);
			}
			if (!empty($post_ids)) {
				$query="DELETE FROM `{$dbtable_prefix}blog_comments` WHERE `fk_parent_id` IN ('".join("','",$post_ids)."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `post_id` IN ('".join("','",$post_ids)."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
			$query="DELETE FROM `{$dbtable_prefix}user_blogs` WHERE `blog_id` IN ('".join("','",$blog_ids)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}

		$query="DELETE FROM `{$dbtable_prefix}blog_comments` WHERE `fk_user_id` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id_other` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}profile_comments` WHERE `fk_user_id` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		require_once _BASEPATH_.'/includes/classes/fileop.class.php';
		$fileop=new fileop();
		$photo_ids=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$photo=mysql_result($res,$i,1);
			$photo_ids[]=mysql_result($res,$i,0);
			$fileop->delete(_PHOTOPATH_.'/t1/'.$photo);
			$fileop->delete(_PHOTOPATH_.'/t2/'.$photo);
			$fileop->delete(_PHOTOPATH_.'/'.$photo);
		}
		$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_parent_id` IN ('".join("','",$photo_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_user_id` IN ('".join("','",$uids[1])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($uids[2])) {
		$query="UPDATE `{$dbtable_prefix}user_blogs` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}blog_posts` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}blog_comments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}profile_comments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_user_id_other`=0 WHERE `fk_user_id_other` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}user_photos` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}photo_comments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids[2])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

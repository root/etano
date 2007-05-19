<?php
$jobs[]='delete_members';

function delete_members() {
	global $dbtable_prefix;

	$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `del`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$uids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$uids[]=mysql_result($res,$i,0);
	}

	if (!empty($uids)) {
		$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}payments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `{$dbtable_prefix}photo_comments` SET `fk_user_id`=0 WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}queue_message` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}queue_message` SET `fk_user_id_other`=0 WHERE `fk_user_id_other` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM ".USER_ACCOUNTS_TABLE." WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_user_id_other`=0 WHERE `fk_user_id_other` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_mtpls` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id_other` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}user_outbox` SET `fk_user_id_other`=0 WHERE `fk_user_id_other` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_spambox` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}user_spambox` SET `fk_user_id_other`=0 WHERE `fk_user_id_other` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		require_once _BASEPATH_.'/includes/classes/modman.class.php';
		$modman=new modman();
		$photo_ids=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$photo=mysql_result($res,$i,1);
			$photo_ids[]=mysql_result($res,$i,0);
			$modman->fileop->delete(_PHOTOPATH_.'/t1/'.$photo);
			$modman->fileop->delete(_PHOTOPATH_.'/t2/'.$photo);
			$modman->fileop->delete(_PHOTOPATH_.'/'.$photo);
		}
		$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_photo_id` IN ('".join("','",$photo_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="DELETE FROM `{$dbtable_prefix}user_searches` WHERE `fk_user_id` IN ('".join("','",$uids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

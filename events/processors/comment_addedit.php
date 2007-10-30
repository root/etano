<?php

$_on_after_approve[]='on_after_approve_comment';

// the calling script MUST HAVE $comment_ids array and $comment_type as GLOBALS
function on_after_approve_comment() {
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

	// only for new comments (because of the processed=0)
	$query="SELECT a.`comment_id`,a.`_user` as `comment_poster`,a.`fk_parent_id`,a.`fk_user_id`,b.`fk_user_id` as `fk_parent_owner_id` FROM $table a,$parent_table b WHERE a.`comment_id` IN ('".join("','",$comment_ids)."') AND a.`fk_parent_id`=b.$parent_key AND a.`processed`=0";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$comment_ids=array();	// yup
	$parent_ids=array();
	$user_ids=array();
	$parent_owner_ids=array();
	$notifs=array();
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
		if ($rsrow['fk_parent_owner_id']!=$rsrow['fk_user_id']) {
			if (!isset($notifs[$rsrow['fk_parent_owner_id']])) {
				$notifs[$rsrow['fk_parent_owner_id']]['comment_poster']=$rsrow['comment_poster'];
				$notifs[$rsrow['fk_parent_owner_id']]['comment_id']=$rsrow['comment_id'];
				$notifs[$rsrow['fk_parent_owner_id']]['parent_id']=$rsrow['fk_parent_id'];
			}
			if (isset($parent_owner_ids[$rsrow['fk_parent_owner_id']])) {
				++$parent_owner_ids[$rsrow['fk_parent_owner_id']];
			} else {
				$parent_owner_ids[$rsrow['fk_parent_owner_id']]=1;
			}
		}
	}
	// increment the number of comments of the item(s)
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
	// add the "received_comment" score to the owner of the item
	foreach ($parent_owner_ids as $uid=>$num) {
		if (!empty($uid)) {
			add_member_score($uid,'received_comment',$num);
		}
	}
	// add the "comments_made" score to the poster of the comment
	foreach ($user_ids as $uid=>$num) {
		if (!empty($uid)) {
			update_stats($uid,'comments_made',$num);
		}
	}
	// mark the posted comment(s) as not new anymore so we won't process them again next time.
	if (!empty($comment_ids)) {
		$query="UPDATE $table SET `processed`=1 WHERE `comment_id` IN ('".join("','",$comment_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	// send notifications to item owners.
	foreach ($notifs as $uid=>$v) {
		$notification['fk_user_id']=$uid;
		$notification['message_type']=MESS_SYSTEM;
		switch ($comment_type) {

			case 'blog':
				$notification['subject']=$GLOBALS['_lang'][160];
				$notification['message_body']=sprintf($GLOBALS['_lang'][161],$v['comment_poster'],$v['parent_id'],$v['comment_id']);
				break;

			case 'photo':
				$notification['subject']=$GLOBALS['_lang'][162];
				$notification['message_body']=sprintf($GLOBALS['_lang'][163],$v['comment_poster'],$v['parent_id'],$v['comment_id']);
				break;

			case 'user':
				$notification['subject']=$GLOBALS['_lang'][164];
				$notification['message_body']=sprintf($GLOBALS['_lang'][165],$v['comment_poster'],$v['comment_id']);
				break;

		}
		queue_or_send_message($notification);
	}
}


$_on_after_approve[]='upd_latest_comm_widg';

// the calling script MUST HAVE $comment_ids array and $comment_type as GLOBALS
function upd_latest_comm_widg() {
	global $dbtable_prefix,$comment_ids,$comment_type;
	if ($comment_type=='blog') {
		$max_title_length=40;
		$config=get_site_option(array('items','enabled'),'latest_blog_comments');
		if (!empty($config['enabled'])) {
			$query="SELECT a.`comment_id`,a.`fk_user_id`,c.`alt_url` as `profile_url`,a.`_user`,b.`post_id`,b.`title`,b.`alt_url` as `post_url` FROM `{$dbtable_prefix}blog_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` c ON a.`fk_user_id`=c.`fk_user_id`,`{$dbtable_prefix}blog_posts` b WHERE a.`fk_parent_id`=b.`post_id` AND a.`status`=".STAT_APPROVED." AND b.`is_public`=1 AND b.`status`=".STAT_APPROVED." ORDER BY a.`date_posted` DESC LIMIT ".$config['items'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$loop=array();
			$i=0;
			while ($rsrow=mysql_fetch_assoc($res)) {
				if (empty($rsrow['profile_url'])) {
					if (!empty($rsrow['fk_user_id'])) {
						$loop[$i]['profile_url']=_BASEURL_.'/profile.php?uid='.$rsrow['fk_user_id'];
					}
				} else {
					$loop[$i]['profile_url']=$rsrow['profile_url'];
				}
				if (empty($rsrow['post_url'])) {
					$loop[$i]['post_url']=_BASEURL_.'/blog_post_view.php?pid='.$rsrow['post_id'].'#comm'.$rsrow['comment_id'];
				} else {
					$loop[$i]['post_url']=$rsrow['post_url'].'#comm'.$rsrow['comment_id'];
				}
				$loop[$i]['user']=$rsrow['_user'];
				if (strlen($rsrow['title'])>$max_title_length) {
					$rsrow['title']=substr($rsrow['title'],0,$max_title_length).'...';
				}
				$loop[$i]['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
				++$i;
			}
			require_once _BASEPATH_.'/includes/classes/fileop.class.php';
			$fileop=new fileop();
			$towrite='<?php $latest_comments='.var_export($loop,true).';';
			$fileop->file_put_contents(_CACHEPATH_.'/widgets/latest_blog_comments/comments.inc.php',$towrite);
		}
	}
}

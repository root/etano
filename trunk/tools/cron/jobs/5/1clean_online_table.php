<?php
$jobs[]='clean_online_table';

function clean_online_table() {
	global $dbtable_prefix;
	$score_threshold=600;	// seconds
	$config=get_site_option(array('inactive_time'),'core');

	$now=gmdate('YmdHis');
	$time=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
	$query="SELECT a.`fk_user_id`,UNIX_TIMESTAMP(b.`last_activity`) as `last_activity` FROM `{$dbtable_prefix}online` a,`".USER_ACCOUNTS_TABLE."` b WHERE a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` AND a.`last_activity`<'$now'-INTERVAL '".$config['inactive_time']."' MINUTE";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$to_del=array();
	$to_score=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$to_del[]=$rsrow['fk_user_id'];
		if ($rsrow['last_activity']<$time-$score_threshold) {
			$to_score[]=$rsrow['fk_user_id'];
		}
	}
	if (!empty($to_del)) {
		add_member_score($to_score,'logout');

		$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `last_activity`='$now' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

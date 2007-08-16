<?php
$jobs[]='clean_online_table';

function clean_online_table() {
	global $dbtable_prefix;
	$config=get_site_option(array('inactive_time'),'core');

	$query="SELECT `fk_user_id`,UNIX_TIMESTAMP(`last_activity`) as `last_activity` FROM `{$dbtable_prefix}online`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$time=gmmktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
	$config['inactive_time']*=60; // to get seconds
	$to_del=array();
	$to_update=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['last_activity']-$config['inactive_time']<=$time) {
			$to_del[]=$rsrow['fk_user_id'];
		} elseif (!empty($rsrow['fk_user_id'])) {
			$to_update[]=$rsrow['fk_user_id'];
		}
	}
	if (!empty($to_del)) {
		add_member_score($to_del,'logout');

		$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	if (!empty($to_update)) {
		$now=gmdate('YmdHis');
		$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `last_activity`='$now' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$to_update)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

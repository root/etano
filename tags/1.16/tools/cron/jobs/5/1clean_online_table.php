<?php
$jobs[]='clean_online_table';

function clean_online_table() {
	global $dbtable_prefix;
	$config=get_site_option(array('inactive_time'),'core');

	$now=gmdate('YmdHis');
	$query="SELECT a.`fk_user_id` FROM `{$dbtable_prefix}online` a WHERE a.`last_activity`<'$now'-INTERVAL '".$config['inactive_time']."' MINUTE";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$to_del=array();
	$to_score=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$to_del[]=$rsrow['fk_user_id'];
	}
	if (!empty($to_del)) {
		add_member_score($to_score,'login',-1);

		$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `last_activity`='$now' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

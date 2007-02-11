<?php
$jobs[]='clean_online_table';

function clean_online_table() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$config=get_site_option(array('inactive_time'),'core');

	$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}online` WHERE `last_activity`<=now()-INTERVAL '".$config['inactive_time']."' MINUTE";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	if (!empty($user_ids)) {
		add_member_score($user_ids,'logout');

		$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id` IN ('".join("','",$user_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

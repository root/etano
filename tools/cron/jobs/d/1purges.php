<?php
$jobs[]='purges';

function purges() {
	global $dbtable_prefix;

	$now=gmdate('YmdHis');
	$config=get_site_option(array('purge_unverified','purge_inbox','purge_trash','purge_folders','purge_outbox'),'core');
	if (!empty($config['purge_unverified'])) {
		$query="SELECT `".USER_ACCOUNT_ID."` FROM ".USER_ACCOUNTS_TABLE." a,`{$dbtable_prefix}user_profiles` b WHERE a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` AND a.`status`='".ASTAT_UNVERIFIED."' AND b.`date_added`<DATE_SUB('$now',INTERVAL ".$config['purge_unverified']." DAY)";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$to_del=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$to_del[]=mysql_result($res,$i,0);
		}
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `del`=1 WHERE `fk_user_id` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($config['purge_inbox'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_inbox']." DAY) AND `del`=0 AND `fk_folder_id`=0";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($config['purge_trash'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_trash']." DAY) AND `del`=1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
print $query;
	if (!empty($config['purge_folders'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_folders']." DAY) AND `del`=0 AND `fk_folder_id`<>0";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($config['purge_outbox'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_outbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_outbox']." DAY)";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

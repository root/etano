<?php
$jobs[]='purges';

function purges() {
	global $dbtable_prefix;

	$now=gmdate('YmdHis');
	$config=get_site_option(array('purge_unverified','purge_inbox','purge_trash','purge_folders','purge_outbox'),'core');

	// these are orpaned accounts, not activated and with no profile data. These should not exist unless a critical error occured during join.
	$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` WHERE a.`status`=".ASTAT_UNVERIFIED." AND b.`fk_user_id` IS NULL";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$to_del=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$to_del[]=mysql_result($res,$i,0);
	}
	if (!empty($to_del)) {
		$query="DELETE FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$to_del)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	// these are orpaned accounts, with profile data but not activated. We cannot delete them but we can't let them like this. We need a profile for them
	$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` WHERE a.`status`=".ASTAT_ACTIVE." AND b.`fk_user_id` IS NULL";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$to_profile=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$to_profile[]=mysql_result($res,$i,0);
	}
	if (!empty($to_profile)) {
		if (get_site_option('manual_profile_approval','core')==1) {
			$pstat=STAT_PENDING;
		} else {
			$pstat=STAT_APPROVED;
		}
		$query="INSERT INTO `{$dbtable_prefix}user_profiles` (`fk_user_id`,`status`,`date_added`,`_user`) SELECT `".USER_ACCOUNT_ID."`,$pstat,'$now',`".USER_ACCOUNT_USER."` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$to_profile)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($config['purge_unverified'])) {
		$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` a,`{$dbtable_prefix}user_profiles` b WHERE a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` AND a.`status`=".ASTAT_UNVERIFIED." AND b.`date_added`<DATE_SUB('$now',INTERVAL ".$config['purge_unverified']." DAY)";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$to_del=array();
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			$to_del[]=mysql_result($res,$i,0);
		}
		if (!empty($to_del)) {
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `del`=1 WHERE `fk_user_id` IN ('".join("','",$to_del)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}

	if (!empty($config['purge_inbox'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_inbox']." DAY) AND `del`=0 AND `fk_folder_id`=0";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	if (!empty($config['purge_trash'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `date_sent`<DATE_SUB('$now',INTERVAL ".$config['purge_trash']." DAY) AND `del`=1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
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

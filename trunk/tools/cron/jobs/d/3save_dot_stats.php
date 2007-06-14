<?php
$jobs[]='save_dot_stats';

function save_dot_stats() {
	global $dbtable_prefix;

	// num users
	$query="SELECT count(*) FROM `{$dbtable_prefix}user_profiles`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$value=mysql_result($res,0,0);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}stats_dot` SET `dataset`='num_users',`value`='$value',`time`='".date('Ymd')."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	return true;
}

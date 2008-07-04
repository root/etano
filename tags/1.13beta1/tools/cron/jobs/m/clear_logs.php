<?php
$jobs[]='clear_logs';

function clear_logs() {
	global $dbtable_prefix;

	// num users
	$query="DELETE FROM `{$dbtable_prefix}site_log` WHERE `time`<DATE_SUB('".gmdate('YmdHis')."',INTERVAL 31 DAY)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	return true;
}

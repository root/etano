<?php
$jobs[]='clean_excess_errors';

// remove half of the existing errors from db if the limit is reached
// this should help minimize the size of the database if something is going horribly wrong.
function clean_excess_errors() {
	global $dbtable_prefix;
	$threshold=50;

	$query="SELECT count(`log_id`) FROM `{$dbtable_prefix}error_log`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_result($res,0,0)>=$threshold) {
		$query="DELETE FROM `{$dbtable_prefix}error_log` ORDER BY `log_id` LIMIT ".((int)($threshold/2));
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

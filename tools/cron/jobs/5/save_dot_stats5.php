<?php
$jobs[]='save_dot_stats5';

function save_dot_stats5() {
	global $dbtable_prefix;

	// max online now
	$query="SELECT count(*) FROM `{$dbtable_prefix}online`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$value=mysql_result($res,0,0);
	$query="SELECT `dot_id`,`value` FROM `{$dbtable_prefix}stats_dot` WHERE `dataset`='online_users' AND `time`='".date('Ymd')."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($dot_id,$old_value)=mysql_fetch_row($res);
		if ($value>$old_value) {
			$query="UPDATE `{$dbtable_prefix}stats_dot` SET `value`='$value' WHERE `dot_id`='$dot_id'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	} else {
		$query="INSERT INTO `{$dbtable_prefix}stats_dot` SET `dataset`='online_users',`value`='$value',`time`='".date('Ymd')."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	return true;
}

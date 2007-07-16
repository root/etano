<?php
$jobs[]='downgrade';

function downgrade() {
	global $dbtable_prefix;

	$now=gmdate('YmdHis');
	$query="SELECT `fk_user_id`,`paid_until` FROM `{$dbtable_prefix}payments` WHERE `paid_until`=curtime()-INTERVAL 1 DAY GROUP BY `fk_user_id` ORDER BY `paid_until` DESC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	return true;
}

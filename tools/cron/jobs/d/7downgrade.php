<?php
$jobs[]='downgrade';

function downgrade() {
	global $dbtable_prefix;

	$now=gmdate('YmdHis');
	$query="SELECT `payment_id`,`fk_user_id` FROM `{$dbtable_prefix}payments` WHERE `paid_until`<=curtime() AND `is_active`=1 AND `paid_until`<>'0000-00-00'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	$payment_ids=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$payment_ids[]=$rsrow['payment_id'];
		$user_ids[]=$rsrow['fk_user_id'];
	}
	$query="UPDATE `{$dbtable_prefix}payments` SET `is_active`=1 WHERE `payment_id` IN ('".join("','",$payment_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `membership`=2 WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$user_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	// it would be nice to send an email notification to these members.
	return true;
}

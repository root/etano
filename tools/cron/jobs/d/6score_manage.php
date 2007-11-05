<?php
$jobs[]='score_manage';

function score_manage() {
	global $dbtable_prefix;

	$now=gmdate('YmdHis');
// take back a fraction of the join score from those who joined in the last 10 days
	$takeback=-add_member_score(0,'join',1,true)/10;
	$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `date_added`>DATE_SUB('$now',INTERVAL 10 DAY)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	if (!empty($user_ids)) {
		add_member_score($user_ids,'force',1,false,$takeback);
	}
	return true;
}

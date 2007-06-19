<?php
/******************************************************************************
Etano
===============================================================================
File:                       tools/gen_member_stats.php
$Revision: 133 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';

mt_srand();
$dot_types=array('num_users','online_users','paid_members');
$days=40;

$type='num_users';
$start_with=0;
$incremental=true;
$rand_max=20;
$rand_min=0;

if (isset($_GET['t'])) {
	if ($_GET['t']=='num_users') {
		$type='num_users';
		$start_with=0;
		$incremental=true;
		$rand_max=20;
		$rand_min=0;
	} elseif ($_GET['t']=='online_users') {
		$type='online_users';
		$start_with=0;
		$incremental=false;
		$rand_max=10;
		$rand_min=0;
	} elseif ($_GET['t']=='paid_members') {
		$type='paid_members';
		$start_with=0;
		$incremental=true;
		$rand_max=3;
		$rand_min=-2;
	}
}

$end_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
$end_date=(int)$end_date;
$query="DELETE FROM `{$dbtable_prefix}stats_dot` WHERE `dataset`='$type'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$prev_value=$start_with;
for ($i=$days;$i>=0;--$i) {
	$time=date('Ymd',$end_date-$i*60*60*24);
	$query="INSERT INTO `{$dbtable_prefix}stats_dot` SET `dataset`='$type',`time`='$time'";
	mt_srand();
	if ($incremental) {
		$prev_value=$prev_value+mt_rand($rand_min,$rand_max);
		$query.=",`value`='$prev_value'";
	} else {
		$query.=",`value`='".mt_rand($rand_min,$rand_max)."'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
print 'done';
?>
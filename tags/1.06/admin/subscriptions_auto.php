<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/subscriptions_auto.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$where='a.`fk_subscr_id`=b.`subscr_id`';
$from="`{$dbtable_prefix}subscriptions_auto` a,`{$dbtable_prefix}subscriptions` b";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$date_format=get_site_option('date_format','def_user_prefs');
$subscriptions_auto=array();
if (!empty($totalrows)) {
	// create the $pfields helper array for easier access to fields by dbfield
	$pfields=array();
	foreach ($_pfields as $pfield_id=>$pfield) {
		if ($pfield['field_type']==FIELD_SELECT) {
			$pfields[$pfield['dbfield']]['label']=$pfield['label'];
			$pfields[$pfield['dbfield']]['accepted_values']=$pfield['accepted_values'];
		}
	}

	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_start`) as `date_start`,b.`subscr_name` FROM $from WHERE $where ORDER BY a.`asubscr_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['dbfield']=empty($rsrow['dbfield']) ? 'All' : 'Having '.$pfields[$rsrow['dbfield']]['label'].'('.$rsrow['dbfield'].') = '.$pfields[$rsrow['dbfield']]['accepted_values'][$rsrow['field_value']];
		$rsrow['subscr_name']=sanitize_and_format($rsrow['subscr_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['date_start']=empty($rsrow['date_start']) ? 'Join' : strftime($date_format,$rsrow['date_start']);
		$subscriptions_auto[]=$rsrow;
	}
}


$tpl->set_file('content','subscriptions_auto.html');
$tpl->set_loop('subscriptions_auto',$subscriptions_auto);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('subscriptions_auto');

$tplvars['title']='Site Auto Subscriptions';
$tplvars['page']='subscriptions_auto';
include 'frame.php';

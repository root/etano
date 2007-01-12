<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/auto_subscriptions.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once 'includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$where='a.`fk_subscr_id`=b.`subscr_id`';
$from="`{$dbtable_prefix}auto_subscriptions` a,`{$dbtable_prefix}subscriptions` b";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$date_format=get_site_option('date_format','core');
$auto_subscriptions=array();
if (!empty($totalrows)) {
	// create the $pfields helper array for easier access to fields by dbfield
	$pfields=array();
	foreach ($_pfields as $pfield_id=>$pfield) {
		if ($pfield['html_type']==_HTML_SELECT_) {
			$pfields[$pfield['dbfield']]['label']=$pfield['label'];
			$pfields[$pfield['dbfield']]['accepted_values']=$pfield['accepted_values'];
		}
	}

	$query="SELECT a.*,UNIX_TIMESTAMP(a.`date_start`) as `date_start`,b.`subscr_name` FROM $from WHERE $where ORDER BY a.`asubscr_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['dbfield']=empty($rsrow['dbfield']) ? 'All' : 'Having '.$pfields[$rsrow['dbfield']]['label'].'('.$rsrow['dbfield'].') = '.$pfields[$rsrow['dbfield']]['accepted_values'][$rsrow['field_value']];
		$rsrow['subscr_name']=sanitize_and_format($rsrow['subscr_name'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$rsrow['date_start']=empty($rsrow['date_start']) ? 'Join' : strftime($date_format,$rsrow['date_start']);
		$auto_subscriptions[]=$rsrow;
	}
}


$tpl->set_file('content','auto_subscriptions.html');
$tpl->set_loop('auto_subscriptions',$auto_subscriptions);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('auto_subscriptions');

$tplvars['title']='Site Auto Subscriptions';
include 'frame.php';
?>
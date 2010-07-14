<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/subscriptions.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$where='a.`m_value_to`=b.`m_value`';
$from="`{$dbtable_prefix}subscriptions` a,`{$dbtable_prefix}memberships` b";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$subscriptions=array();
if (!empty($totalrows)) {
	$query="SELECT a.`subscr_id`,a.`subscr_name`,a.`price`,a.`currency`,a.`is_recurent`,b.`m_name` as `m_value_to`,a.`duration`,a.`is_visible` FROM $from WHERE $where ORDER BY a.`subscr_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['currency']=isset($accepted_currencies[$rsrow['currency']]) ? $accepted_currencies[$rsrow['currency']] : '';
		$rsrow['subscr_name']=sanitize_and_format($rsrow['subscr_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (!empty($rsrow['is_recurent'])) {
			$rsrow['is_recurent']='<img src="skin/images/refresh.gif" title="Recuring" />';
		} else {
			unset($rsrow['is_recurent']);
		}
		if (!empty($rsrow['is_visible'])) {
			$rsrow['is_visible']='<img src="skin/images/check.gif" />';
		} else {
			unset($rsrow['is_visible']);
		}
		if (empty($rsrow['duration'])) {
			$rsrow['duration']='Lifetime';
		} else {
			$rsrow['duration'].=' days';
		}
		$subscriptions[]=$rsrow;
	}
}

$tpl->set_file('content','subscriptions.html');
$tpl->set_loop('subscriptions',$subscriptions);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('subscriptions');

$tplvars['title']='Site Subscriptions';
$tplvars['page']='subscriptions';
include 'frame.php';

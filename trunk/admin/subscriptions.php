<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/subscriptions.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$where='a.`m_value_from`=b.`m_value` AND a.`m_value_to`=c.`m_value`';
$from="`{$dbtable_prefix}subscriptions` a,`{$dbtable_prefix}memberships` b,`{$dbtable_prefix}memberships` c";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$subscriptions=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT a.*,b.`m_name` as `m_value_from`,c.`m_name` as `m_value_to` FROM $from WHERE $where ORDER BY a.`subscr_id`";
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
?>
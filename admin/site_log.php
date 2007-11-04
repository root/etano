<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_log.php
$Revision: 322 $
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

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}site_log`";

$output=array();
$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>=$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$config=get_site_option(array('datetime_format'),'def_user_prefs');
	$query="SELECT `fk_user_id`,`user`,`level_code`,`ip`,UNIX_TIMESTAMP(`time`) as `time` FROM $from WHERE $where ORDER BY `log_id` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (!empty($rsrow['fk_user_id'])) {
			$rsrow['user']='<a href="profile.php?uid='.$rsrow['fk_user_id'].'">'.$rsrow['user'].'</a>';
		}
		$rsrow['time']=strftime($config['datetime_format'],$rsrow['time']);
		$rsrow['ip']=long2ip($rsrow['ip']);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$tpl->set_file('content','site_log.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Site Activity Log';
$tplvars['page']='site_log';
$tplvars['css']='site_log.css';
include 'frame.php';

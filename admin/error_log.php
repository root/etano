<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/error_log.php
$Revision: 322 $
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

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}error_log`";

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
	$query="SELECT `log_id`,`module`,`error`,UNIX_TIMESTAMP(`error_date`) as `error_date` FROM $from WHERE $where ORDER BY `log_id` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['error_date']=strftime($config['datetime_format'],$rsrow['error_date']);
		$rsrow['error']=sanitize_and_format(substr($rsrow['error'],0,200),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='error_log.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','error_log.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Error Log';
$tplvars['page']='error_log';
$tplvars['css']='error_log.css';
include 'frame.php';

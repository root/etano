<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_news.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}site_news`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT * FROM $from WHERE $where ORDER BY `news_id` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['news_title']=sanitize_and_format($rsrow['news_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='site_news.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','site_news.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Site news';
$tplvars['page']='site_news';
include 'frame.php';
?>
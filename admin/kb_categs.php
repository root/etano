<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/kb_categs.php
$Revision: 207 $
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

$dbtable_prefix='';

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}kb_categs` a LEFT JOIN `{$dbtable_prefix}kb_categs` b ON b.`kbc_id`=a.`fk_kbc_id_parent`";

$query="SELECT count(*) FROM `{$dbtable_prefix}kb_categs`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$output=array();
$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT a.`kbc_id`,a.`kbc_title`,a.`num_articles`,b.`kbc_id` as `parent_id`,b.`kbc_title` as `parent_title` FROM $from WHERE $where ORDER BY a.`fk_kbc_id_parent`,a.`kbc_id` LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['kbc_title']=sanitize_and_format($rsrow['kbc_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['parent_title']=sanitize_and_format($rsrow['parent_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$loop[]=$rsrow;
		++$i;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='kb_categs.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.str_replace('&','&amp;',$_SERVER['QUERY_STRING']);
}
$tpl->set_file('content','kb_categs.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('countries');

$tplvars['title']='Knowledge Base Categories Management';
$tplvars['page']='kb_categs';
include 'frame.php';

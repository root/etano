<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile_categories.php
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
require_once '../includes/tables/profile_categories.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : -1;
$od=isset($_GET['od']) ? (int)$_GET['od'] : 0;
$orderkeys=array_keys($profile_categories_default['defaults']);
$orderby='';
if ($ob>=0) {
	$orderby='ORDER BY `'.$orderkeys[$ob].'`';
	if ($od==0) {
		$orderby.=' ASC';
	} else {
		$orderby.=' DESC';
	}
}

$default_skin_code=get_default_skin_code();
$where='1';
$from="`{$dbtable_prefix}profile_categories` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_pcat`=b.`fk_lk_id` AND b.`skin`='$default_skin_code')";

$query="SELECT count(*) FROM $from WHERE $where";
$temp=md5($query);
if (isset($_SESSION['admin']['cache'][$temp]['time']) && $_SESSION['admin']['cache'][$temp]['time']>=time()-600) {
	$totalrows=$_SESSION['admin']['cache'][$temp]['count'];
} else {
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);
	$_SESSION['admin']['cache'][$temp]['time']=time();
	$_SESSION['admin']['cache'][$temp]['count']=$totalrows;
}

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT `m_value`,`m_name` FROM `{$dbtable_prefix}memberships`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$memberships=array();
	while ($rsrow=mysql_fetch_row($res)) {
		$memberships[$rsrow[0]]=$rsrow[1];
	}

	$query="SELECT a.*,b.`lang_value` as `pcat_name` FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$loop[$i]=$rsrow;
		$loop[$i]=sanitize_and_format($loop[$i],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$loop[$i]['access_level']=get_level_name($loop[$i]['access_level'],$memberships);
		++$i;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='profile_categories.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.str_replace('&','&amp;',$_SERVER['QUERY_STRING']);
}
$output['return2me']=rawurlencode($output['return2me']);

$tpl->set_file('content','profile_categories.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Profile Categories Management';
$tplvars['css']='profile_categories.css';
$tplvars['page']='profile_categories';
include 'frame.php';

function get_level_name($binvalue,&$memberships) {
//print $binvalue.' ';
	$myreturn='';
	foreach ($memberships as $k=>$v) {
		if (((int)$binvalue)&((int)$k)) {
			$myreturn.=$v.', ';
		}
	}
	if (!empty($myreturn)) {
		$myreturn=substr($myreturn,0,-2);
	}
	return $myreturn;
}

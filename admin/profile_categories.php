<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_categories.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/profile_categories.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','profile_categories.html');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
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

$where='1';
$from="`{$dbtable_prefix}profile_categories` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_pcat`=b.`fk_lk_id` AND b.`skin`='"._DEFAULT_SKIN_."')";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$profile_categories=array();
if (!empty($totalrows)) {
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
		$profile_categories[$i]=$rsrow;
		$profile_categories[$i]=sanitize_and_format($profile_categories[$i],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$profile_categories[$i]['access_level']=get_level_name($profile_categories[$i]['access_level'],$memberships);
		++$i;
	}
	$tpl->set_var('pager1',create_pager2($totalrows,$o,$r));
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}

$tpl->set_loop('profile_categories',$profile_categories);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('profile_categories');

$tplvars['title']='Profile Categories Management';
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
?>
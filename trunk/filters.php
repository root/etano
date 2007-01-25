<?php
/******************************************************************************
newdsb
===============================================================================
File:                       filters.php
$Revision: 0 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/tables/message_filters.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(4);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : 1;
$od=isset($_GET['od']) ? (int)$_GET['od'] : 1;
$orderkeys=array_keys($message_filters_default['defaults']);
$orderby='';
if ($ob>=0) {
	$orderby='ORDER BY `'.$orderkeys[$ob].'`';
	if ($od==0) {
		$orderby.=' ASC';
	} else {
		$orderby.=' DESC';
	}
}

$folders=array();
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$folders[$rsrow[0]]=$rsrow[1];
}
if (!empty($folders)) {
	$tpl->set_var('folder_options',vector2options($folders));
}

$from="`{$dbtable_prefix}message_filters`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$filters=array();
if (!empty($totalrows)) {
	$query="SELECT * FROM $from WHERE $where $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($folders[$rsrow['fk_folder_id']]) && ($rsrow['fk_folder_id']>0)) {
			$rsrow['folder_name']=$folders[$rsrow['fk_folder_id']];
		} else {
			$rsrow['folder_name']='SPAMBOX';
		}
		$filters[]=$rsrow;
	}
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}

$tpl->set_file('content','filters.html');
$tpl->set_loop('filters',$filters);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('filters');

if (is_file('filters_left.php')) {
	include 'filters_left.php';
}
$tplvars['title']='Manage your filters';     // translate
include 'frame.php';

?>
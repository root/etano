<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/site_skins.php
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
require_once '../includes/tables/site_skins.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : -1;
$od=isset($_GET['od']) ? (int)$_GET['od'] : 0;
$orderkeys=array_keys($site_skins_default['defaults']);
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
$from="`{$dbtable_prefix}site_skins` a";

$site_skins=array();
$query="SELECT * FROM $from WHERE $where $orderby";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$site_skins[$i]=$rsrow;
	$site_skins[$i]=sanitize_and_format($site_skins[$i],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	if (!empty($site_skins[$i]['is_default'])) {
		$site_skins[$i]['is_default']='<img src="skin/images/check.gif" />';
	} else {
		unset($site_skins[$i]['is_default']);
	}
	++$i;
}

$tpl->set_file('content','site_skins.html');
$tpl->set_loop('site_skins',$site_skins);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('ob',$ob);
$tpl->set_var('od',$od);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('site_skins');

$tplvars['title']='Skin Settings';
include 'frame.php';
?>
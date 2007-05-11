<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/access_levels.php
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

$query="SELECT * FROM `{$dbtable_prefix}memberships` ORDER BY `m_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$memberships=array();
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$memberships[$i]=$rsrow;
	if ($memberships[$i]['is_custom']) {
		$memberships[$i]['m_name'].=' <a href="javascript:;" onclick="del_membership('.$memberships[$i]['m_id'].')" title="Delete this membership"><img src="skin/images/del.gif" alt="Delete this membership" /></a>';
	}
	++$i;
}

$query="SELECT * FROM `{$dbtable_prefix}access_levels`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=0;
$access_levels=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$access_levels[$i]['row']='<td>'.$rsrow['level_id']."</td>\n<td><a href=\"access_levels_addedit.php?level_id=".$rsrow['level_id'].'" title="'.$rsrow['level_diz'].'">'.$rsrow['level_code']."</a></td>\n";
	for ($j=0;isset($memberships[$j]);++$j) {
		$access_levels[$i]['row'].='<td><input type="checkbox" name="levels['.$rsrow['level_id'].']['.$memberships[$j]['m_value'].']" value="1"';
		if (((int)$memberships[$j]['m_value']) & ((int)$rsrow['level'])) {
			$access_levels[$i]['row'].=' checked="checked"';
		}
		if (((int)$memberships[$j]['m_value']) & ((int)$rsrow['disabled_level'])) {
			$access_levels[$i]['row'].=' disabled="disabled"';
		}
		$access_levels[$i]['row'].=" /></td>\n";
	}
	++$i;
}

$tpl->set_file('content','access_levels.html');
$tpl->set_loop('access_levels',$access_levels);
$tpl->set_loop('memberships',$memberships);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('access_levels');
$tpl->drop_loop('memberships');
unset($access_levels);
unset($memberships);

$tplvars['title']='Define access levels';
$tplvars['page']='access_levels';
include 'frame.php';
?>
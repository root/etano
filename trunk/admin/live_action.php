<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/live_action.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$config=get_site_option(array('datetime_format'),'def_user_prefs');
$query="SELECT `log_id`,`fk_user_id`,`user`,`level_code`,`ip`,UNIX_TIMESTAMP(`time`) as `time` FROM `{$dbtable_prefix}site_log` ORDER BY `log_id` DESC limit 10";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$loop=array();
$last_id=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	if (!empty($rsrow['fk_user_id'])) {
		$rsrow['user']='<a href="profile.php?uid='.$rsrow['fk_user_id'].'">'.$rsrow['user'].'</a>';
	}
	if (empty($last_id)) {
		$last_id=$rsrow['log_id'];
	}
	$rsrow['ip']=long2ip($rsrow['ip']);
	$rsrow['time']=strftime($config['datetime_format'],$rsrow['time']);
	$loop[]=$rsrow;
}

$tpl->set_file('content','live_action.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('last_id',$last_id);
$tpl->process('content','content',TPL_LOOP);

$tplvars['title']='Live Site Activity';
$tplvars['css']='live_action.css';
$tplvars['page']='live_action';
include 'frame.php';

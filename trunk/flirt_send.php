<?php
/******************************************************************************
newdsb
===============================================================================
File:                       flirt_send.php
$Revision: 52 $
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(2);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$uid=0;
$_user_other='';
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(string)((int)$_GET['uid']);
	$flirts=array();
	$_user_other=get_user_by_userid($uid);
	$query="SELECT `flirt_id`,`flirt_text` FROM `{$dbtable_prefix}flirts`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_row($res)) {
		$flirts[$rsrow[0]]=$rsrow[1];
	}
	if (!empty($flirts)) {
		$tpl->set_var('flirts_options',vector2radios($flirts,'flirt'));
	}
} 

$tpl->set_file('content','flirt_send.html');
$tpl->set_var('uid',$uid);
$tpl->set_var('_user_other',$_user_other);
$tpl->process('content','content');

if (is_file('flirt_send_left.php')) {
	include 'flirt_send_left.php';
}
$tplvars['title']='Send a flirt';     // translate
include 'frame.php';
?>
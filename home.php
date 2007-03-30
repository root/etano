<?php
/******************************************************************************
newdsb
===============================================================================
File:                       home.php
$Revision$
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
check_login_member(3);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$query="SELECT `_photo` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$tplvars['myself']['photo']=mysql_result($res,0,0);
if (empty($tplvars['myself']['photo'])) {
	$tplvars['myself']['photo']='no-photo.gif';
}

$user_stats=get_module_stats(1,$_SESSION['user']['user_id']);

$tpl->set_file('content','home.html');
$tpl->set_var('user_stats',$user_stats);
$tpl->process('content','content');

$tplvars['title']='Member Home';
$tplvars['page_title']='My Home';
$tplvars['page']='home';
$tplvars['css']='home.css';
if (is_file('home_left.php')) {
	include 'home_left.php';
}
include 'frame.php';
?>
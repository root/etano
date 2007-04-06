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
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(3);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$query="SELECT `_photo` as `photo`,UNIX_TIMESTAMP(`date_added`) as `date_added` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output=mysql_fetch_assoc($res);
if (empty($output['photo'])) {
	$output['photo']='no-photo.gif';
}
$output['date_added']=strftime($_user_settings['date_format'],$output['date_added']+$_user_settings['time_offset']);

$my_stats=get_user_stats($_SESSION['user']['user_id'],array('total_messages','new_messages','total_photos','pviews','num_friends'));

$tpl->set_file('content','home.html');
$tpl->set_var('output',$output);
$tpl->set_var('my_stats',$my_stats);
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
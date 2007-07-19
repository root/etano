<?php
/******************************************************************************
Etano
===============================================================================
File:                       home.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$query="SELECT `_photo` as `photo`,UNIX_TIMESTAMP(`date_added`) as `date_added` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$_SESSION['user']['user_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
	$output['date_added']=strftime($_SESSION['user']['prefs']['date_format'],$output['date_added']+$_SESSION['user']['prefs']['time_offset']);
}
if (empty($output['photo'])) {
	$output['photo']='no_photo.gif';
}

$my_stats=get_user_stats($_SESSION['user']['user_id'],array('total_photos','pviews','num_friends'));
$query="SELECT count(*) FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id`=".$_SESSION['user']['user_id']." AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$my_stats['total_messages']=mysql_result($res,0,0);
$query="SELECT count(*) FROM `{$dbtable_prefix}user_inbox` WHERE `is_read`=0 AND `fk_user_id`=".$_SESSION['user']['user_id']." AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$my_stats['new_messages']=mysql_result($res,0,0);

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

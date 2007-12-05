<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/cpanel.php
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
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();

$query="SELECT count(*) FROM `{$dbtable_prefix}user_profiles` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_profiles']=mysql_result($res,0,0);
if (empty($output['pending_profiles'])) {
	unset($output['pending_profiles']);
}
$query="SELECT count(*) FROM `{$dbtable_prefix}blog_posts` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_blogs']=mysql_result($res,0,0);
if (empty($output['pending_blogs'])) {
	unset($output['pending_blogs']);
}
$query="SELECT count(*) FROM `{$dbtable_prefix}user_photos` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_photos']=mysql_result($res,0,0);
if (empty($output['pending_photos'])) {
	unset($output['pending_photos']);
}

$query="SELECT count(*) FROM `{$dbtable_prefix}profile_comments` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_profile_comments']=mysql_result($res,0,0);
if (empty($output['pending_profile_comments'])) {
	unset($output['pending_profile_comments']);
}
$query="SELECT count(*) FROM `{$dbtable_prefix}blog_comments` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_blog_comments']=mysql_result($res,0,0);
if (empty($output['pending_blog_comments'])) {
	unset($output['pending_blog_comments']);
}
$query="SELECT count(*) FROM `{$dbtable_prefix}photo_comments` WHERE `status`=".STAT_PENDING;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['pending_photo_comments']=mysql_result($res,0,0);
if (empty($output['pending_photo_comments'])) {
	unset($output['pending_photo_comments']);
}

if (!isset($output['pending_profiles'])&& !isset($output['pending_blogs']) && !isset($output['pending_photos']) && !isset($output['pending_profile_comments'])&& !isset($output['pending_blog_comments']) && !isset($output['pending_photo_comments'])) {
	$output['none_pending']=true;
}

$tpl->set_file('content','cpanel.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Your admin control panel';
$tplvars['page']='cpanel';
$tplvars['css']='cpanel.css';
include 'frame.php';

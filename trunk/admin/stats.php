<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/stats.php
$Revision: 133 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();

$query="SELECT count(*) FROM `{$dbtable_prefix}user_profiles`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['num_members']=mysql_result($res,0,0);

$query="SELECT count(*) FROM `{$dbtable_prefix}user_photos`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['num_photos']=mysql_result($res,0,0);

$query="SELECT count(*) FROM `{$dbtable_prefix}user_blogs`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['num_blogs']=mysql_result($res,0,0);

$query="SELECT count(*) FROM `{$dbtable_prefix}blog_posts`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output['num_blog_posts']=mysql_result($res,0,0);

$tpl->set_file('content','stats.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Your admin control panel';
$tplvars['page']='stats';
include 'frame.php';
?>
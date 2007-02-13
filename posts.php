<?php
/******************************************************************************
newdsb
===============================================================================
File:                       posts.php
$Revision: 21 $
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
check_login_member(10);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$blog_posts_stats=array();
if (isset($_SESSION['user']['user_id'])) {
	$block_posts_stats=get_module_stats(2,$_SESSION['user']['user_id']);
}

$query="SELECT `post_id`,`title`,`post_content`,`_user` as `user`,`fk_user_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}blog_posts` ORDER BY `date_posted` DESC LIMIT 3";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$latest_blog_posts=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['date_posted']=strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
	$latest_blog_posts[]=$rsrow;
}
$latest_blog_posts=sanitize_and_format($latest_blog_posts,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

$featured_blog_posts=array();

$tpl->set_file('content','posts.html');
if (!empty($blog_posts_stats)) {
	$tpl->set_var('blog_posts_stats',$blog_posts_stats);
}
$tpl->set_loop('latest_blog_posts',$latest_blog_posts);
$tpl->set_loop('featured_blog_posts',$featured_blog_posts);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTIONAL | TPL_OPTLOOP);
$tpl->drop_loop('latest_blog_posts');
$tpl->drop_loop('featured_blog_posts');

$tplvars['title']='Blog posts';
include 'frame.php';
?>
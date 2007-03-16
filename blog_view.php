<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_view.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(10);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$blog=array();
if (isset($_GET['bid']) && !empty($_GET['bid'])) {
	$blog_id=(int)$_GET['bid'];
	include _CACHEPATH_.'/blogs/'.$blog_id.'/blog.inc.php';
	$query="SELECT `post_id`,`title`,`post_content`,`_user` as `user`,`fk_user_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id`='$blog_id' ORDER BY `date_posted` DESC LIMIT 5";
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['date_posted']=strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
	if (empty($rsrow['fk_user_id'])) {
		unset($rsrow['fk_user_id']);
	}
	$loop[]=$rsrow;
}
$loop=sanitize_and_format($loop,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

$tpl->set_file('content','blog_view.html');
$tpl->set_var('blog',$blog);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Blog posts';
$tplvars['page_title']=$blog['blog_name'];
$tplvars['page']='blog_view';
$tplvars['css']='blog_view.css';
if (is_file('blog_view_left.php')) {
	include 'blog_view_left.php';
}
include 'frame.php';
?>
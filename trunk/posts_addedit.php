<?php
/******************************************************************************
newdsb
===============================================================================
File:                       posts_addedit.php
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
require_once 'includes/tables/blog_posts.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(11);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$blog_posts=$blog_posts_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$blog_posts=$_SESSION['topass']['input'];
} elseif (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
	$post_id=(int)$_GET['post_id'];
	$query="SELECT * FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='$post_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$blog_posts=mysql_fetch_assoc($res);
		$blog_posts=sanitize_and_format($blog_posts,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}

if (!empty($blog_posts['allow_comments'])) {
	$blog_posts['allow_comments']='checked';
}

$tpl->set_file('content','posts_addedit.html');
$tpl->set_var('blog_posts',$blog_posts);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
$tpl->process('content','content');

$tplvars['title']='Add your post';
include 'frame.php';
?>
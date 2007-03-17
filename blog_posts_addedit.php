<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_posts_addedit.php
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

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=$blog_posts_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} elseif (isset($_GET['bid']) && !empty($_GET['bid'])) {
	$output['fk_blog_id']=(int)$_GET['bid'];
} elseif (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
	$post_id=(int)$_GET['post_id'];
	$query="SELECT `post_id`,`fk_blog_id`,`title`,`post_content`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='$post_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output=sanitize_and_format($output,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}

if (!empty($output['allow_comments'])) {
	$output['allow_comments']='checked="checked"';
}
if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=rawurlencode(sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
}

$tplvars['bbcode_blogs']=get_site_option('bbcode_blogs','core_blog');
if (empty($tplvars['bbcode_blogs'])) {
	unset($tplvars['bbcode_blogs']);
}

include _CACHEPATH_.'/blogs/'.$output['fk_blog_id'].'/blog.inc.php';
$tpl->set_file('content','blog_posts_addedit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Add your post';
$tplvars['page_title']=sprintf('Add/Edit a Post in %s',$blog['blog_name']);
$tplvars['page']='blog_posts_addedit';
$tplvars['css']='blog_posts_addedit.css';
if (is_file('blog_posts_addedit_left.php')) {
	include 'blog_posts_addedit_left.php';
}
include 'frame.php';
?>
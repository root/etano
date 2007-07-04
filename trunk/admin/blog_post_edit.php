<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/blog_post_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} elseif (!empty($_GET['post_id'])) {
	$post_id=(int)$_GET['post_id'];
	$query="SELECT `post_id`,`fk_blog_id`,`title`,`post_content`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='$post_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

if (!empty($output['allow_comments'])) {
	$output['allow_comments']='checked="checked"';
}
if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUENCODE,'');
}

$output['bbcode_blogs']=get_site_option('bbcode_blogs','core_blog');
if (empty($output['bbcode_blogs'])) {
	unset($output['bbcode_blogs']);
}

$tpl->set_file('content','blog_post_edit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Edit a Blog Post';
$tplvars['page']='blog_post_edit';
$tplvars['css']='blog_post_edit.css';
include 'frame.php';
?>
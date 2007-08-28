<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/blog_posts_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/triggers.inc.php';
check_login_member('write_blogs');

if (is_file(_BASEPATH_.'/events/processors/blog_posts_delete.php')) {
	include_once _BASEPATH_.'/events/processors/blog_posts_delete.php';
}

$topass=array();
$post_id=isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}blog_comments` WHERE `fk_parent_id`=$post_id AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (isset($_on_before_delete)) {
	for ($i=0;isset($_on_before_delete[$i]);++$i) {
		eval($_on_before_delete[$i].'();');
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

on_before_delete_blog_post(array($post_id));

$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=$post_id AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Post and comments deleted.';     // translate

$nextpage='my_blog_posts.php';
if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
}
if (isset($_on_after_delete)) {
	for ($i=0;isset($_on_after_delete[$i]);++$i) {
		eval($_on_after_delete[$i].'();');
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

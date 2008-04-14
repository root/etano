<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/blog_post_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$post_id=isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=$post_id";
if (is_file(_BASEPATH_.'/events/processors/blog_posts_delete.php')) {
	include_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/blogs.inc.php';
	include_once _BASEPATH_.'/events/processors/blog_posts_delete.php';
	if (isset($_on_before_delete)) {
		$GLOBALS['post_ids']=array($post_id);
		for ($i=0;isset($_on_before_delete[$i]);++$i) {
			call_user_func($_on_before_delete[$i]);
		}
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}comments_blog` WHERE `fk_parent_id`=$post_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

// what to do with the cache for the deleted blog post?

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Post and all related comments deleted.';

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/blog_search.php';
}
redirect2page($nextpage,$topass,'',true);

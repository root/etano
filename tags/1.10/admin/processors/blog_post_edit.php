<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/blog_post_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/blog_posts.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='blog_search.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$blog_posts_default['defaults']['allow_comments']=0;
// get the input we need and sanitize it
	foreach ($blog_posts_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$blog_posts_default['defaults'][$k]);
	}
// max 2 empty lines
	$input['post_content']=preg_replace(array('/\\\r\\\n/','/(\\\n\s+\\\n)+/','/(\\\n){3,}/'),array('\n','\n','\n\n'),$input['post_content']);

	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please add a title for this post';
	}
	if (empty($input['post_content'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please write something in the post';
	}

	if (!$error) {
		$input['title']=remove_banned_words($input['title']);
		$input['post_content']=remove_banned_words($input['post_content']);
		unset($input['fk_user_id'],$input['fk_blog_id']);
		$towrite=array();	// what to write in the cache file

		$query="UPDATE `{$dbtable_prefix}blog_posts` SET `last_changed`='".gmdate('YmdHis')."',`status`=".STAT_APPROVED;
		foreach ($blog_posts_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.=",`$k`='".$input[$k]."'";
				$towrite[$k]=$input[$k];
			}
		}
		$query.=" WHERE `post_id`=".$input['post_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Post changed successfully.';
	} else {
		$nextpage='blog_post_edit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['post_content']=addslashes_mq($_POST['post_content']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/flirts_addedit.php
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='comment_addedit.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['m']=sanitize_and_format_gpc($_POST,'m',TYPE_STRING,0,'');

	$default['defaults']=array();
	if ($input['m']=='blog') {
		require _BASEPATH_.'/includes/tables/comments_blog.inc.php';
		$default=&$comments_blog_default;
		$table="`{$dbtable_prefix}comments_blog`";
		$parent_table="`{$dbtable_prefix}blog_posts`";
		$parent_key="`post_id`";
	} elseif ($input['m']=='photo') {
		require _BASEPATH_.'/includes/tables/comments_photo.inc.php';
		$default=&$comments_photo_default;
		$table="`{$dbtable_prefix}comments_photo`";
		$parent_table="`{$dbtable_prefix}user_photos`";
		$parent_key="`photo_id`";
	} elseif ($input['m']=='user') {
		require _BASEPATH_.'/includes/tables/comments_profile.inc.php';
		$default=&$comments_profile_default;
		$table="`{$dbtable_prefix}comments_profile`";
	}
	foreach ($default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$default['defaults'][$k]);
	}
	unset($input['fk_user_id']);
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format($_POST['return'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE);
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['comment'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the comment';
		$input['error_comment']='red_border';
	}

	if (!$error) {
		$now=gmdate('YmdHis');
		$input['comment']=remove_banned_words($input['comment']);
		if (!empty($input['comment_id'])) {
			$query="UPDATE $table SET `last_changed`='$now'";
			foreach ($default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `comment_id`=".$input['comment_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Comment changed.';
		} else {
			unset($input['comment_id']);
			$query="INSERT INTO $table SET `_user`='Admin',`date_posted`='$now',`last_changed`='$now',`status`=".STAT_APPROVED;
			foreach ($default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Comment added.';
		}
	} else {
		$nextpage='comment_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['comment']=addslashes_mq($_POST['comment']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

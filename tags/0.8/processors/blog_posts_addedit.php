<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/blog_posts_addedit.php
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
require_once '../includes/tables/blog_posts.inc.php';
require_once '../includes/triggers.inc.php';
check_login_member('write_blogs');

if (is_file(_BASEPATH_.'/events/processors/blog_posts_addedit.php')) {
	include_once _BASEPATH_.'/events/processors/blog_posts_addedit.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_blog_posts.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$blog_posts_default['defaults']['allow_comments']=0;
// get the input we need and sanitize it
	foreach ($blog_posts_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$blog_posts_default['defaults'][$k]);
	}
// max 2 empty lines
	$input['post_content']=preg_replace(array('/\\\r\\\n/','/(\\\n\s+\\\n)+/','/(\\\n){3,}/'),array('\n','\n','\n\n'),$input['post_content']);

	$input['fk_user_id']=$_SESSION['user']['user_id'];
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
		$config=get_site_option(array('manual_blog_approval'),'core_blog');
		$towrite=array();	// what to write in the cache file
		if (!empty($input['post_id'])) {
			$query="UPDATE `{$dbtable_prefix}blog_posts` SET `last_changed`='".gmdate('YmdHis')."'";
			if ($config['manual_blog_approval']==1) {
				// set to pending only if the title or content was changed.
				$query2="SELECT `title`,`post_content` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='".$input['post_id']."'";
				if (!($res=@mysql_query($query2))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$rsrow=sanitize_and_format(mysql_fetch_assoc($res),TYPE_STRING,$__field2format[TEXT_DB2DB]);
					if (strcmp($rsrow['title'],$input['title'])!=0 || strcmp($rsrow['post_content'],$input['post_content'])!=0) {
						$query.=",`status`='".STAT_PENDING."'";
					}
				}
			} else {
				$query.=",`status`='".STAT_APPROVED."'";
			}
			foreach ($blog_posts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
					$towrite[$k]=$input[$k];
				}
			}
			$query.=" WHERE `post_id`='".$input['post_id']."'";
			if (isset($_on_before_update)) {
				for ($i=0;isset($_on_before_update[$i]);++$i) {
					eval($_on_before_update[$i].'();');
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Post changed successfully.';
			if (isset($_on_after_update)) {
				for ($i=0;isset($_on_after_update[$i]);++$i) {
					eval($_on_after_update[$i].'();');
				}
			}
		} else {
			$now=gmdate('YmdHis');
			unset($input['post_id']);
			$query="INSERT INTO `{$dbtable_prefix}blog_posts` SET `_user`='".$_SESSION['user']['user']."',`date_posted`='$now',`last_changed`='$now'";
			if ($config['manual_blog_approval']) {
				$query.=",`status`='".STAT_PENDING."'";
			} else {
				$query.=",`status`='".STAT_APPROVED."'";
			}
			foreach ($blog_posts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
					$towrite[$k]=$input[$k];
				}
			}
			if (isset($_on_before_insert)) {
				for ($i=0;isset($_on_before_insert[$i]);++$i) {
					eval($_on_before_insert[$i].'();');
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['post_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;

			if (empty($config['manual_blog_approval'])) {
				$query="UPDATE `{$dbtable_prefix}user_blogs` SET `stat_posts`=`stat_posts`+1 WHERE `blog_id`='".$input['fk_blog_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['text']='Post added.';	// translate this
			} else {
				$topass['message']['text']='Post added. It will be reviewed and published shortly.';	// translate this
			}

			if (empty($config['manual_blog_approval'])) {
				on_approve_blog_post(array($input['post_id']));
			}
			if (isset($_on_after_insert)) {
				for ($i=0;isset($_on_after_insert[$i]);++$i) {
					eval($_on_after_insert[$i].'();');
				}
			}
		}
		if (!isset($input['return']) || empty($input['return'])) {
			$qs.=$qs_sep.'bid='.$input['fk_blog_id'];
			$qs_sep='&';
			$nextpage.='?'.$qs;
		}
	} else {
		$nextpage='blog_posts_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['post_content']=addslashes_mq($_POST['post_content']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				eval($_on_error[$i].'();');
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

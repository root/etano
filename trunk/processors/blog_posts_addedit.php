<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/blog_posts_addedit.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
require_once '../includes/tables/blog_posts.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(11);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_blog_posts.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($blog_posts_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$blog_posts_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];
	if (isset($_POST['return']) && !empty($_POST['return'])) {
		$input['return']=rawurldecode(sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
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
		$config=get_site_option(array('manual_blog_approval'),'core_blog');
		$towrite=array();	// what to write in the cache file
		if (!empty($input['post_id'])) {
			$query="UPDATE `{$dbtable_prefix}blog_posts` SET `last_changed`='".gmdate('YmdHis')."'";
			if ($config['manual_blog_approval']==1) {
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
			$query.=" WHERE `post_id`='".$input['post_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Post changed successfully.';
		} else {
			$now=gmdate('YmdHis');
			unset($input['post_id']);
			$query="INSERT INTO `{$dbtable_prefix}blog_posts` SET `_user`='".$_SESSION['user']['user']."',`date_posted`='$now',`last_changed`='$now'";
			if ($config['manual_blog_approval']==1) {
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

			// update the blog_archive links now if this is auto approved
			if (empty($config['manual_blog_approval'])) {
				update_stats($_SESSION['user']['user_id'],'blog_posts',1);

				require_once '../includes/classes/modman.class.php';
				$modman=new modman();

				$blog_archive=array();
				if (is_file(_CACHEPATH_.'/blogs/'.$input['fk_blog_id'].'/blog_archive.inc.php')) {
					include _CACHEPATH_.'/blogs/'.$input['fk_blog_id'].'/blog_archive.inc.php';
				}
				if (isset($blog_archive[(int)date('Y')][(int)date('m')])) {
					++$blog_archive[(int)date('Y')][(int)date('m')];
				} else {
					$blog_archive[(int)date('Y')][(int)date('m')]=1;
				}
				krsort($blog_archive,SORT_NUMERIC);
				$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$input['fk_blog_id'].'/blog_archive.inc.php','<?php $blog_archive='.var_export($blog_archive,true).';');
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
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
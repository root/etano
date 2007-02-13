<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/posts_addedit.php
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
$nextpage='posts.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($blog_posts_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$blog_posts_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];

	if (!$error) {
		if (!empty($input['post_id'])) {
			$query="UPDATE `{$dbtable_prefix}blog_posts` SET `last_changed`='".gmdate('YmdHis')."'";
			if (get_site_option('manual_blog_approval','core_blog')==1) {
				$query.=",`status`='".PSTAT_PENDING."'";
			} else {
				$query.=",`status`='".PSTAT_APPROVED."'";
			}
			foreach ($blog_posts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `post_id`='".$input['post_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Post changed successfully.';
		} else {
			$now=gmdate('YmdHis');
			$query="INSERT INTO `{$dbtable_prefix}blog_posts` SET `_user`='".$_SESSION['user']['user']."',`date_posted`='$now',`last_changed`='$now'";
			if (get_site_option('manual_blog_approval','core_blog')==1) {
				$query.=",`status`='".PSTAT_PENDING."'";
			} else {
				$query.=",`status`='".PSTAT_APPROVED."'";
			}
			foreach ($blog_posts_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Post saved.';
		}
	} else {
		$nextpage='posts_addedit.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
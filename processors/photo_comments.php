<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/photo_comments.php
$Revision: 70 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/tables/photo_comments.inc.php';
check_login_member(9);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='photo_view.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($photo_comments_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$photo_comments_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

	if (!$error) {
		$config=get_site_option(array('manual_com_approval'),'core');
		if (!empty($input['comment_id'])) {
			$query="UPDATE `{$dbtable_prefix}photo_comments` SET `last_changed`='".gmdate('YmdHis')."'";
			if ($config['manual_com_approval']==1) {
				$query.=",`status`='".STAT_PENDING."'";
			} else {
				$query.=",`status`='".STAT_APPROVED."'";
			}
			foreach ($photo_comments_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `comment_id`='".$input['comment_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Comment changed successfully.';
		} else {
			unset($input['comment_id']);
			$now=gmdate('YmdHis');
			$query="INSERT INTO `{$dbtable_prefix}photo_comments` SET `_user`='".$_SESSION['user']['user']."',`date_posted`='$now',`last_changed`='$now'";
			if ($config['manual_com_approval']==1) {
				$query.=",`status`='".STAT_PENDING."'";
			} else {
				$query.=",`status`='".STAT_APPROVED."'";
			}
			foreach ($photo_comments_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			update_stats($_SESSION['user']['user_id'],'comments',1);
			if (empty($config['manual_com_approval'])) {
				$query="UPDATE `{$dbtable_prefix}user_photos` SET `stat_comments`=`stat_comments`+1 WHERE `photo_id`='".$input['fk_photo_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['text']='Comment added.';	// translate this
				$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='".$input['fk_photo_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$notification['fk_user_id']=mysql_result($res,0,0);
					$notification['subject']='New comment on one of your photos';	// translate
					$notification['message_body']=sprintf('%1s posted a comment on one of your photos.<br><a class="content-link simple" href="photo_view.php?photo_id=%2s">Click here</a> to view the comment',$_SESSION['user']['user'],$input['fk_photo_id']);
					$notification['message_type']=MESS_SYSTEM;
					queue_or_send_message($notification);
				}
			} else {
				$topass['message']['text']='Comment added but needs to be approved first.';	// translate this
			}
		}
		$qs.=$qs_sep.'photo_id='.$input['fk_photo_id'];
		$qs_sep='&';
		$qs.=$qs_sep.'return='.$input['return'];
	}
}
redirect2page($nextpage,$topass,$qs);
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/profile_comments.php
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
require_once '../includes/tables/profile_comments.inc.php';
check_login_member(9);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='profile.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($profile_comments_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$profile_comments_default['defaults'][$k]);
	}
	$input['fk_user_id']=isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0;
	if (isset($_POST['return']) && !empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	} else {
		$input['return']='';
	}

	if (empty($input['comment'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']="Please enter your comment.";
	}
	if (!$error && $input['fk_user_id']==0 && get_site_option('use_captcha','core')) {
		$captcha=sanitize_and_format_gpc($_POST,'captcha',TYPE_STRING,0,'');
		if (!$error && (!isset($_SESSION['captcha_word']) || strcasecmp($captcha,$_SESSION['captcha_word'])!=0)) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']="The verification code doesn't match. Please enter the new code.";
			$input['error_captcha']='red_border';
		}
	}
	unset($_SESSION['captcha_word']);

	if (!$error) {
		$config=get_site_option(array('manual_com_approval'),'core');
		$input['comment']=remove_banned_words($input['comment']);
		if (!empty($input['comment_id'])) {
			// only members can edit their comments
			if (isset($_SESSION['user']['user_id'])) {
				$input['comment'].="\n\nLast edited by ".$_SESSION['user']['user'].' on '.gmdate('Y-m-d H:i:s').' GMT';
				$query="UPDATE `{$dbtable_prefix}profile_comments` SET `last_changed`='".gmdate('YmdHis')."'";
				if ($config['manual_com_approval']==1) {
					$query.=",`status`='".STAT_PENDING."'";
				} else {
					$query.=",`status`='".STAT_APPROVED."'";
				}
				foreach ($profile_comments_default['defaults'] as $k=>$v) {
					if (isset($input[$k])) {
						$query.=",`$k`='".$input[$k]."'";
					}
				}
				$query.=" WHERE `comment_id`='".$input['comment_id']."' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Comment changed successfully.';
			} else {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='You are not allowed to edit comments';
			}
		} else {
			unset($input['comment_id']);
			$now=gmdate('YmdHis');
			$query="INSERT INTO `{$dbtable_prefix}profile_comments` SET `_user`='".$_SESSION['user']['user']."',`date_posted`='$now',`last_changed`='$now'";
			if ($config['manual_com_approval']==1) {
				$query.=",`status`='".STAT_PENDING."'";
			} else {
				$query.=",`status`='".STAT_APPROVED."'";
			}
			foreach ($profile_comments_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['comment_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;
			if (isset($_SESSION['user']['user_id'])) {
				update_stats($_SESSION['user']['user_id'],'comments_made',1);
			}
			if (empty($config['manual_com_approval'])) {
				update_stats($input['fk_parent_id'],'profile_comments',1);
				$topass['message']['text']='Comment added.';	// translate this
				$notification['fk_user_id']=$input['fk_parent_id'];
				// send notif only if it's not my blog
				if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']!=$notification['fk_user_id']) {
					$notification['subject']='New comment on your profile';	// translate
					$notification['message_body']=sprintf('%1$s posted a comment on your profile.<br><a class="content-link simple" href="my_profile.php#comm%2$s">Click here</a> to view the comment',$_SESSION['user']['user'],$input['comment_id']);
					$notification['message_type']=MESS_SYSTEM;
					queue_or_send_message($notification);
				}
				$nextpage.='#comm'.$input['comment_id'];
			} else {
				$topass['message']['text']='Comment added but needs to be reviewed first.';	// translate this
			}
		}
	} else {
		$input['comment']=addslashes_mq($_POST['comment']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
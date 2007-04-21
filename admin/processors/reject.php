<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/reject.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$ok=true;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['t']=sanitize_and_format_gpc($_POST,'t',TYPE_INT,0,0);
	$input['id']=sanitize_and_format_gpc($_POST,'id',TYPE_INT,0,0);
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD] | FORMAT_RUDECODE,'');
	$input['send_email']=sanitize_and_format_gpc($_POST,'send_email',TYPE_INT,0,0);
	$input['reject_reason']=sanitize_and_format_gpc($_POST,'reject_reason',TYPE_STRING,$__html2format[HTML_TEXTAREA],'');
	$input['reason_title']=sanitize_and_format_gpc($_POST,'reason_title',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

	switch ($input['t']) {

		case AMTPL_REJECT_MEMBER:
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `status`='".STAT_EDIT."',`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `fk_user_id`='".$input['id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT `email` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id`='".$input['id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$_POST['reject_reason']));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			break;

		case AMTPL_REJECT_PHOTO:
			$query="UPDATE `{$dbtable_prefix}user_photos` SET `status`='".STAT_EDIT."',`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `photo_id`='".$input['id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT b.`email` FROM `{$dbtable_prefix}user_photos` a,".USER_ACCOUNTS_TABLE." b WHERE a.`fk_user_id`=b.`user_id` AND a.`photo_id`='".$input['id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$_POST['reject_reason']));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			break;

		case AMTPL_REJECT_BLOG:
			$query="UPDATE `{$dbtable_prefix}blog_posts` SET `status`='".STAT_EDIT."',`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `post_id`='".$input['id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT b.`email` FROM `{$dbtable_prefix}blog_posts` a,".USER_ACCOUNTS_TABLE." b WHERE a.`fk_user_id`=b.`user_id` AND a.`post_id`='".$input['id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$_POST['reject_reason']));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			break;

	}
}

$nextpage=_BASEURL_.'/admin/member_search.php';
if (isset($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
}
redirect2page($nextpage,$topass,$qs,true);
?>
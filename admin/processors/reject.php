<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/reject.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$ok=true;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='GET') {
	$_POST=$_GET;
}
$input=array();
// get the input we need and sanitize it
$input['t']=sanitize_and_format_gpc($_POST,'t',TYPE_INT,0,0);
$input['id']=sanitize_and_format_gpc($_POST,'id',TYPE_INT,0,0);
$input['send_email']=sanitize_and_format_gpc($_POST,'send_email',TYPE_INT,0,0);
$input['reject_reason']=isset($_POST['reject_reason']) ? $_POST['reject_reason'] : '';
$input['reason_title']=sanitize_and_format_gpc($_POST,'reason_title',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
if (!empty($_POST['return'])) {
	$input['return']=sanitize_and_format($_POST['return'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE);
	$nextpage=$input['return'];
}

if (!empty($input['send_email'])) {
	if (empty($input['reason_title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the subject.';
	}
	if (empty($input['reject_reason'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the reason.';
	}
}

if (!$error) {
	$tpl=new phemplate(_BASEPATH_.'/skins_site/'.$def_skin.'/','remove_nonjs');
	$tpl->set_file('temp','emails/general.html');
	$tpl->set_var('output.content',$input['reject_reason']);
	$tpl->set_var('tplvars',$tplvars);
	$input['reject_reason']=$tpl->process('temp','temp',TPL_FINISH | TPL_OPTIONAL | TPL_INCLUDE);
	$tpl->drop_var('temp');
	$tpl->drop_var('output.content');
	$reject_reason_email=$input['reject_reason'];	// for email
	$input['reject_reason']=sanitize_and_format($input['reject_reason'],TYPE_STRING,$__field2format[FIELD_TEXTAREA]);

	switch ($input['t']) {

		case AMTPL_REJECT_MEMBER:
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `status`=".STAT_EDIT.",`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `fk_user_id`=".$input['id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT `email` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."`=".$input['id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$reject_reason_email));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			if (empty($nextpage)) {
				$nextpage='member_search.php';
			}
			break;

		case AMTPL_REJECT_PHOTO:
			if (is_file(_BASEPATH_.'/events/processors/photo_delete.php')) {
				include_once _BASEPATH_.'/events/processors/photo_delete.php';
				if (isset($_on_before_delete)) {
					$GLOBALS['photo_ids']=array($input['id']);
					for ($i=0;isset($_on_before_delete[$i]);++$i) {
						call_user_func($_on_before_delete[$i]);
					}
				}
			}
			$query="UPDATE `{$dbtable_prefix}user_photos` SET `status`=".STAT_EDIT.",`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `photo_id`=".$input['id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT b.`email` FROM `{$dbtable_prefix}user_photos` a,`".USER_ACCOUNTS_TABLE."` b WHERE a.`photo_id`=".$input['id']." AND a.`fk_user_id`=b.`".USER_ACCOUNT_ID."`";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$_POST['reject_reason']));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			if (empty($nextpage)) {
				$nextpage='photo_search.php';
			}
			break;

		case AMTPL_REJECT_BLOG:
			$query="UPDATE `{$dbtable_prefix}blog_posts` SET `status`=".STAT_EDIT.",`last_changed`='".gmdate('YmdHis')."',`reject_reason`='".$input['reject_reason']."' WHERE `post_id`=".$input['id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['send_email'])) {
				$query="SELECT b.`email` FROM `{$dbtable_prefix}blog_posts` a,`".USER_ACCOUNTS_TABLE."` b WHERE a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` AND a.`post_id`=".$input['id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$ok=queue_or_send_email(array(mysql_result($res,0,0)),array('subject'=>$_POST['reason_title'],'message_body'=>$_POST['reject_reason']));
				}
			}
			if ($ok) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Ok.';
			}
			if (empty($nextpage)) {
				$nextpage='blog_search.php';
			}
			break;

	}
} else {
	$nextpage='reject.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
	$input['reject_reason']=addslashes_mq($_POST['reject_reason']);
	$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
	$topass['input']=$input;
}

$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

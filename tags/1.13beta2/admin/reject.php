<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/reject.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$returned=false;
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return']=rawurlencode($output['return']);
	$returned=true;
} else {
	$output['t']=sanitize_and_format_gpc($_GET,'t',TYPE_INT,0,0);
	$output['id']=sanitize_and_format_gpc($_GET,'id',TYPE_INT,0,0);
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
	$output['m']=sanitize_and_format_gpc($_GET,'m',TYPE_STRING,0,'');
}

$query="SELECT `amtpl_id`,`amtpl_name`,`subject`,`message_body` FROM `{$dbtable_prefix}admin_mtpls` WHERE `amtpl_type`=".$output['t'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$amtpls=array();
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$amtpls[$rsrow['amtpl_id']]=$rsrow['amtpl_name'];
	if ($i==0 && !$returned) {
		$output['reason_title']=$rsrow['subject'];
		$output['reject_reason']=$rsrow['message_body'];
	}
	$i++;
}
$output['amtpl_id']=vector2options($amtpls);
switch ($output['t']) {

	case AMTPL_REJECT_MEMBER:
		$output['user_id']=$output['id'];
		$output['user']=get_user_by_userid($output['id']);
		$output['reject_member']=true;
		$tplvars['title']='Reject a member profile';
		break;

	case AMTPL_REJECT_PHOTO:
		$query="SELECT `fk_user_id` as `user_id`,`_user` as `user`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=".$output['id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($output['user_id'],$output['user'],$output['photo'])=mysql_fetch_row($res);
		}
		$output['reject_photo']=true;
		$tplvars['title']='Reject a photo';
		break;

	case AMTPL_REJECT_BLOG:
		$query="SELECT `fk_user_id` as `user_id`,`_user` as `user`,`title` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=".$output['id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=array_merge($output,mysql_fetch_assoc($res));
			$output['title']=sanitize_and_format($output['title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY],'');
		}
		$output['reject_blog']=true;
		$tplvars['title']='Reject a blog post';
		break;

	case AMTPL_REJECT_COMM:
		switch ($output['m']) {

			case 'blog':
				$table="`{$dbtable_prefix}comments_blog`";
				break;

			case 'photo':
				$table="`{$dbtable_prefix}comments_photo`";
				break;

			case 'user':
				$table="`{$dbtable_prefix}comments_profile`";
				break;

		}
		$query="SELECT `comment` FROM $table WHERE `comment_id`=".$output['id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=array_merge($output,mysql_fetch_assoc($res));
			$output['comment']=sanitize_and_format($output['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY],'');
		}
		$output['reject_comment']=true;
		$tplvars['title']='Reject a comment';
		break;
}

$tpl->set_file('content','reject.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['css']='reject.css';
$tplvars['page']='reject';
include 'frame.php';

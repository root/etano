<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/reject.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$input=array();
$input['t']=sanitize_and_format_gpc($_GET,'t',TYPE_INT,0,0);
$input['id']=sanitize_and_format_gpc($_GET,'id',TYPE_INT,0,0);
$input['return']=rawurlencode(sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],''));

$query="SELECT `amtpl_id`,`amtpl_name`,`subject`,`message_body` FROM `{$dbtable_prefix}admin_mtpls` WHERE `amtpl_type`='".$input['t']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$amtpls=array();
$reason_title='';
$reject_reason='';
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	$amtpls[$rsrow['amtpl_id']]=$rsrow['amtpl_name'];
	if ($i==0) {
		$input['reason_title']=$rsrow['subject'];
		$input['reject_reason']=$rsrow['message_body'];
	}
	$i++;
}
$input['amtpl_id']=vector2options($amtpls);
switch ($input['t']) {

	case AMTPL_REJECT_MEMBER:
		$input['user_id']=$input['id'];
		$input['user']=get_user_by_userid($input['id']);
		$input['reject_member']=true;
		break;

	case AMTPL_REJECT_PHOTO:
		$query="SELECT `fk_user_id` as `user_id`,`_user` as `user`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='".$input['id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($input['user_id'],$input['user'],$input['photo'])=mysql_fetch_row($res);
		}
		$input['reject_photo']=true;
		break;

	case AMTPL_REJECT_BLOG:
		$query="SELECT `fk_user_id` as `user_id`,`_user` as `user` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='".$input['id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($input['user_id'],$input['user'])=mysql_fetch_row($res);
		}
		$input['reject_blog']=true;
		break;

}

$tpl->set_file('content','reject.html');
$tpl->set_var('input',$input);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Reject a member profile';
include 'frame.php';
?>
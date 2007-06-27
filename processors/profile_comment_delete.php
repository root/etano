<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/profile_comment_delete.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
check_login_member('auth');

$topass=array();
$comment_id=isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;

if (!empty($comment_id)) {
	// delete only if I am the owner of the profile this comment's been made on
	$query="DELETE FROM `{$dbtable_prefix}profile_comments` WHERE `comment_id`='$comment_id' AND `fk_parent_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_affected_rows()) {
		update_stats($_SESSION['user']['user_id'],'profile_comments',-1);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Comment deleted.';     // translate
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='You are not allowed to delete this comment.';     // translate
	}
}

if (isset($_GET['return']) && !empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
} else {
	$nextpage='home.php';
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
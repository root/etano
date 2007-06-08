<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/photo_comment_delete.php
$Revision: 67 $
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
check_login_member('auth');

if (is_file(_BASEPATH_.'/events/processors/photo_comment_delete.php')) {
	include_once _BASEPATH_.'/events/processors/photo_comment_delete.php';
}

$topass=array();
$comment_id=isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;

if (!empty($comment_id)) {
	$query="SELECT b.`fk_user_id`,a.`fk_parent_id` FROM `{$dbtable_prefix}photo_comments` a,`{$dbtable_prefix}user_photos` b WHERE a.`comment_id`='$comment_id' AND a.`fk_parent_id`=b.`photo_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)==$_SESSION['user']['user_id']) {
		$parent_id=mysql_result($res,0,1);
		// delete only if I am the owner of the original photo this comment's been made on
		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `comment_id`='$comment_id'";
		if (isset($_on_before_delete)) {
			for ($i=0;isset($_on_before_delete[$i]);++$i) {
				eval($_on_before_delete[$i].'();');
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `{$dbtable_prefix}user_photos` SET `stat_comments`=`stat_comments`-1 WHERE `photo_id`='$parent_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Comment deleted.';     // translate
		if (isset($_on_after_delete)) {
			for ($i=0;isset($_on_after_delete[$i]);++$i) {
				eval($_on_after_delete[$i].'();');
			}
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='You are not allowed to delete this comment.';     // translate
	}
}

$nextpage='home.php';
if (isset($_GET['return']) && !empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
	$nextpage=$input['return'];
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
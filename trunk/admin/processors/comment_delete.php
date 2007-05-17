<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/comment_delete.php
$Revision: 67 $
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
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$comment_id=isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
$m=sanitize_and_format_gpc($_GET,'m',TYPE_STRING,0,'');
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

switch ($m) {

	case 'blog':
		$query="DELETE FROM `{$dbtable_prefix}blog_comments` WHERE `comment_id`='$comment_id'";
		break;

	case 'photo':
		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `comment_id`='$comment_id'";
		break;

	case 'user':
		$query="DELETE FROM `{$dbtable_prefix}profile_comments` WHERE `comment_id`='$comment_id'";
		break;

}
if (!empty($query)) {
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Comment deleted.';
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Comment not found.';
}

if (isset($return) && !empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/comment_search.php';
}
redirect2page($nextpage,$topass,'',true);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/blog_post_delete.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$post_id=isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="DELETE FROM `{$dbtable_prefix}blog_comments` WHERE `fk_parent_id`='$post_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`='$post_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Post and all related comments deleted.';     // translate

if (isset($return) && !empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/blog_search.php';
}
redirect2page($nextpage,$topass,'',true);
?>
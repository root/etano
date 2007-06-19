<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/photos_delete.php
$Revision: 21 $
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
$photo_id=isset($_GET['photo_id']) ? (int)$_GET['photo_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `fk_user_id`,`photo`,`is_main` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$input=mysql_fetch_assoc($res);
	$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	require_once '../../includes/classes/modman.class.php';
	$modman=new modman();
	$modman->fileop->delete(_PHOTOPATH_.'/t1/'.$input['photo']);
	$modman->fileop->delete(_PHOTOPATH_.'/t2/'.$input['photo']);
	$modman->fileop->delete(_PHOTOPATH_.'/'.$input['photo']);

	$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_parent_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

// what to do with the cache for the deleted comments or photo page? clear_cache($photo_id) ????

	if ($input['is_main']==1) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`='".$input['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Photo deleted.';
}

if (isset($return) && !empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/photo_search.php';
}
redirect2page($nextpage,$topass,'',true);
?>
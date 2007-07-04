<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/photos_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
check_login_member('upload_photos');

if (is_file(_BASEPATH_.'/events/processors/photo_delete.php')) {
	include_once _BASEPATH_.'/events/processors/photo_delete.php';
}

$qs='';
$qs_sep='';
$topass=array();
$photo_id=isset($_GET['photo_id']) ? (int)$_GET['photo_id'] : 0;

$query="SELECT `photo`,`is_main` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$input=mysql_fetch_assoc($res);
	if (!empty($input['photo'])) {
		$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
		if (isset($_on_before_delete)) {
			for ($i=0;isset($_on_before_delete[$i]);++$i) {
				eval($_on_before_delete[$i].'();');
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		require_once '../includes/classes/modman.class.php';
		$modman=new modman();
		$modman->fileop->delete(_PHOTOPATH_.'/t1/'.$input['photo']);
		$modman->fileop->delete(_PHOTOPATH_.'/t2/'.$input['photo']);
		$modman->fileop->delete(_PHOTOPATH_.'/'.$input['photo']);

		$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_parent_id`='$photo_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	// what to do with the cache for the deleted comments or photo page? clear_cache($photo_id) ????

		if ($input['is_main']==1) {
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			add_member_score($uid,'del_main_photo');
		} else {
			add_member_score($uid,'del_photo');
		}
		update_stats($_SESSION['user']['user_id'],'total_photos',-1);
	}

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Photo deleted.';

// trigger generate_fields

	if (isset($_on_after_delete)) {
		for ($i=0;isset($_on_after_delete[$i]);++$i) {
			eval($_on_after_delete[$i].'();');
		}
	}
}

$nextpage='my_photos.php';
if (!empty($_POST['return'])) {
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
	$nextpage=$input['return'];
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
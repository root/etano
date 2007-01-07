<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/photos_delete.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
require_once '../includes/classes/modman.class.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(8);

$qs='';
$qs_sep='';
$topass=array();
$photo_id=isset($_GET['photo_id']) ? (int)$_GET['photo_id'] : 0;

$query="SELECT `photo`,`is_main` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$input=mysql_fetch_assoc($res);
	$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	$modman=new modman();
	$modman->fileop->delete(_BASEPATH_.'/media/pics/t1/'.$input['photo']);
	$modman->fileop->delete(_BASEPATH_.'/media/pics/t2/'.$input['photo']);
	$modman->fileop->delete(_BASEPATH_.'/media/pics/'.$input['photo']);

	$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_photo_id`='$photo_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

// what to do with the cache for the deleted comments or photo page? clear_cache($photo_id) ????

	if ($input['is_main']==1) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Photo deleted.';

// trigger generate_fields
}

if (isset($_POST['o'])) {
	$qs.=$qs_sep.'o='.$_POST['o'];
	$qs_sep='&';
}
if (isset($_POST['r'])) {
	$qs.=$qs_sep.'r='.$_POST['r'];
	$qs_sep='&';
}
redirect2page('user_photos.php',$topass,$qs);
?>
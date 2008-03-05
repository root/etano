<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/photo_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/photos.inc.php';
check_login_member('upload_photos');

if (is_file(_BASEPATH_.'/events/processors/photo_delete.php')) {
	include_once _BASEPATH_.'/events/processors/photo_delete.php';
}

$topass=array();
$photo_id=isset($_GET['photo_id']) ? (int)$_GET['photo_id'] : 0;

$query="SELECT `photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$photo_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$input=mysql_fetch_assoc($res);
	$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$photo_id";
	if (isset($_on_before_delete)) {
		$GLOBALS['photo_ids']=array($photo_id);
		for ($i=0;isset($_on_before_delete[$i]);++$i) {
			call_user_func($_on_before_delete[$i]);
		}
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (!empty($input['photo'])) {
		require_once '../includes/classes/fileop.class.php';
		$fileop=new fileop();
		$fileop->delete(_PHOTOPATH_.'/t1/'.$input['photo']);
		$fileop->delete(_PHOTOPATH_.'/t2/'.$input['photo']);
		$fileop->delete(_PHOTOPATH_.'/'.$input['photo']);
	}
	$photos_remaining=get_user_settings($_SESSION[_LICENSE_KEY_]['user']['user_id'],'core_photo','max_user_photos');
	if ($photos_remaining!=-1) {
		set_user_settings($_SESSION[_LICENSE_KEY_]['user']['user_id'],'core_photo','max_user_photos',$photos_remaining+1);
	}

	$query="DELETE FROM `{$dbtable_prefix}photo_comments` WHERE `fk_parent_id`=$photo_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	// what to do with the cache for the deleted comments or photo page? clear_cache($photo_id) ????

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']=$GLOBALS['_lang'][91];

	if (isset($_on_after_delete)) {
		for ($i=0;isset($_on_after_delete[$i]);++$i) {
			call_user_func($_on_after_delete[$i]);
		}
	}
}

$nextpage='my_photos.php';
if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

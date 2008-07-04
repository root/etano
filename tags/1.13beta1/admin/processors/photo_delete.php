<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/photo_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$photo_id=isset($_GET['photo_id']) ? (int)$_GET['photo_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `fk_user_id`,`photo`,`is_main` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$photo_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$input=mysql_fetch_assoc($res);

	$query="DELETE FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=$photo_id";
	if (is_file(_BASEPATH_.'/events/processors/photo_delete.php')) {
		include_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/photos.inc.php';
		include_once _BASEPATH_.'/events/processors/photo_delete.php';
		if (isset($_on_before_delete)) {
			$GLOBALS['photo_ids']=array($photo_id);
			for ($i=0;isset($_on_before_delete[$i]);++$i) {
				call_user_func($_on_before_delete[$i]);
			}
		}
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	if (!empty($input['photo'])) {
		require_once '../../includes/classes/fileop.class.php';
		$fileop=new fileop();
		$fileop->delete(_PHOTOPATH_.'/t1/'.$input['photo']);
		$fileop->delete(_PHOTOPATH_.'/t2/'.$input['photo']);
		$fileop->delete(_PHOTOPATH_.'/'.$input['photo']);
	}

	$query="DELETE FROM `{$dbtable_prefix}comments_photo` WHERE `fk_parent_id`=$photo_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

// what to do with the cache for the deleted comments or photo page? clear_cache($photo_id) ????

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Photo deleted.';
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/photo_search.php';
}
redirect2page($nextpage,$topass,'',true);

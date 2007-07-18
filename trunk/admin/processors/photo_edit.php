<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/photo_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/user_photos.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($user_photos_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_photos_default['defaults'][$k]);
	}
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	if (!$error) {
		unset($input['photo'],$input['status'],$input['date_posted'],$input['last_changed']);
		if (!empty($input['photo_id'])) {
			$query="SELECT `photo_id` FROM `{$dbtable_prefix}user_photos` WHERE `is_main`=1 AND `fk_user_id`=".$input['fk_user_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$old_main=0;
			if (mysql_num_rows($res)) {
				$old_main=mysql_result($res,0,0);
			}
			// handle main profile photo
			if (!empty($input['is_main']) && $old_main!=$input['photo_id']) {
				$query="UPDATE `{$dbtable_prefix}user_photos` SET `is_main`=0 WHERE `fk_user_id`=".$input['fk_user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="SELECT `photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=".$input['photo_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$output['photo']=mysql_result($res,0,0);
				$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='".$output['photo']."',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`=".$input['fk_user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			} elseif (empty($input['is_main']) && $old_main==$input['photo_id']) {
				$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='',`last_changed`='".gmdate('YmdHis')."' WHERE `fk_user_id`=".$input['fk_user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
			$query="UPDATE `{$dbtable_prefix}user_photos` SET `last_changed`='".gmdate('YmdHis')."'";
			foreach ($user_photos_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `photo_id`=".$input['photo_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Photo info changed.';
		}
	} else {
		$nextpage='photo_edit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
if (empty($nextpage)) {
	$nextpage=_BASEURL_.'/admin/photo_search.php';
	if (isset($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	}
}
redirect2page($nextpage,$topass,$qs,true);

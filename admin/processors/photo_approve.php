<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/photo_approve.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (!empty($_GET['photo_id'])) {
	$input['photo_id']=(int)$_GET['photo_id'];
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	$query="UPDATE `{$dbtable_prefix}user_photos` SET `status`=".STAT_APPROVED.",`reject_reason`='',`last_changed`='".gmdate('YmdHis')."' WHERE `photo_id`=".$input['photo_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	$query="SELECT `processed` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`=".$input['photo_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rsrow=mysql_fetch_assoc($res);
		if ($rsrow['processed']) {
			$do_stats=false;
		} else {
			$do_stats=true;
		}
		if (is_file(_BASEPATH_.'/events/processors/photos_upload.php')) {
			include_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/photos.inc.php';
			include_once _BASEPATH_.'/events/processors/photos_upload.php';
			if (isset($_on_after_approve)) {
				$GLOBALS['photo_ids']=array($input['photo_id']);
				for ($i=0;isset($_on_after_approve[$i]);++$i) {
					call_user_func($_on_after_approve[$i]);
				}
			}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Photo approved.';
	}
}

if (isset($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
} else {
	$nextpage=_BASEURL_.'/admin/photo_search.php';
}
redirect2page($nextpage,$topass,'',true);

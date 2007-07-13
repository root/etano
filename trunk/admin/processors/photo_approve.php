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
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/triggers.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (!empty($_GET['photo_id'])) {
	$input['photo_id']=(int)$_GET['photo_id'];
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	$query="UPDATE `{$dbtable_prefix}user_photos` SET `status`='".STAT_APPROVED."',`reject_reason`='',`last_changed`='".gmdate('YmdHis')."' WHERE `photo_id`='".$input['photo_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	$query="SELECT `is_main`,`fk_user_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='".$input['photo_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rsrow=mysql_fetch_assoc($res);
		// make this photo the main photo if it is_main
		if (!empty($rsrow['is_main'])) {
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `_photo`='".$rsrow['photo']."' WHERE `fk_user_id`='".$rsrow['fk_user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		on_approve_photo(array($input['photo_id']));
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

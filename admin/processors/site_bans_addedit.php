<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_bans_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/site_bans.inc.php';
require_once '../../includes/logs.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='site_bans.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($site_bans_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$site_bans_default['defaults'][$k]);
	}
	$input['reason']=sanitize_and_format_gpc($_POST,'reason',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}


// check for input errors
	if (empty($input['ban_type'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select the type of the ban';
		$input['error_ban_type']='red_border';
	}
	if (empty($input['what'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the username of the IP address to ban';
		$input['error_what']='red_border';
	}
	if ($input['ban_type']==_PUNISH_BANIP_ && (ip2long($input['what'])==-1 || ip2long($input['what'])===false)) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid IP address entered';
		$input['error_what']='red_border';
	}

	if (!$error) {
		if ($input['ban_type']==_PUNISH_BANIP_) {
			$input['what']=sprintf('%u',ip2long($input['what']));
		}
		$default_skin_code=get_default_skin_code();
		if (!empty($input['ban_id'])) {
			$query="UPDATE `{$dbtable_prefix}site_bans` SET ";
			foreach ($site_bans_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `ban_id`='".$input['ban_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="REPLACE INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['reason']."',`fk_lk_id`='".$input['fk_lk_id_reason']."',`skin`='$default_skin_code'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Ban changed.';
		} else {
			unset($input['ban_id']);
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Ban reason',`lk_use`='".LK_MESSAGE."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_reason']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` (`lang_value`,`fk_lk_id`,`skin`) VALUES ('".$input['reason']."','".$input['fk_lk_id_reason']."','$default_skin_code')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			$query="INSERT INTO `{$dbtable_prefix}site_bans` SET ";
			foreach ($site_bans_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Ban added.';
		}
		regenerate_langstrings_array();
		regenerate_ban_array();
	} else {
		$nextpage='site_bans_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
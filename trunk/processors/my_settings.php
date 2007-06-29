<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/my_settings.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
check_login_member('auth');

if (is_file(_BASEPATH_.'/events/processors/my_settings.php')) {
	include_once _BASEPATH_.'/events/processors/my_settings.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$types=array();

	$query="SELECT `config_option`,`option_type`,`fk_module_code` FROM `{$dbtable_prefix}site_options3` WHERE `per_user`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$types[$rsrow['fk_module_code']][$rsrow['config_option']]=$rsrow['option_type'];
		switch ($rsrow['option_type']) {

			case FIELD_CHECKBOX:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD],0);
				break;

			case FIELD_TEXTFIELD:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
				break;

			case FIELD_INT:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_INT,0,0);
				break;

			case FIELD_TEXTAREA:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
				break;

			case FIELD_SELECT:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_INT,0,0);
				break;

		}
	}

	foreach ($input as $module_code=>$v) {
		foreach ($v as $config_option=>$config_value) {
			// with this if() we target date_format because an empty date_format
			// could break all dates on the site.
			if ($types[$module_code][$config_option]!=FIELD_TEXTFIELD || !empty($config_value)) {
				$query="REPLACE INTO `{$dbtable_prefix}user_settings2` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`config_option`='$config_option',`config_value`='$config_value',`fk_module_code`='$module_code'";
				if (isset($_on_before_update)) {
					for ($i=0;isset($_on_before_update[$i]);++$i) {
						eval($_on_before_update[$i].'();');
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (isset($_on_after_update)) {
					for ($i=0;isset($_on_after_update[$i]);++$i) {
						eval($_on_after_update[$i].'();');
					}
				}
			}
		}
	}

	// update the prefs stored in the session
	$_SESSION['user']['prefs']['date_format']=$input['def_user_prefs']['date_format'];
	$_SESSION['user']['prefs']['datetime_format']=$input['def_user_prefs']['datetime_format'];
	$_SESSION['user']['prefs']['time_offset']=$input['def_user_prefs']['time_offset'];
	$_SESSION['user']['prefs']['rate_my_photos']=$input['def_user_prefs']['rate_my_photos'];
	$_SESSION['user']['prefs']['profile_comments']=$input['def_user_prefs']['profile_comments'];
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Your preferences were saved.';
}
redirect2page('my_settings.php',$topass,$qs);
?>
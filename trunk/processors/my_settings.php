<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/my_settings.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
check_login_member(3);

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

			case HTML_CHECKBOX:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__html2format[HTML_TEXTFIELD],0);
				break;

			case HTML_TEXTFIELD:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
				break;

			case HTML_INT:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_INT,0,0);
				break;

			case HTML_TEXTAREA:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__html2format[HTML_TEXTAREA],'');
				break;

		}
	}

	foreach ($input as $module_code=>$v) {
		foreach ($v as $config_option=>$config_value) {
			// with this if() we target date_format because an empty date_format
			// could break all dates on the site.
			if ($types[$module_code][$config_option]!=HTML_TEXTFIELD || !empty($config_value)) {
				$query="REPLACE INTO `{$dbtable_prefix}user_settings2` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`config_option`='$config_option',`config_value`='$config_value',`fk_module_code`='$module_code'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
	}
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Your preferences were saved.';
}
redirect2page('my_settings.php',$topass,$qs);
?>
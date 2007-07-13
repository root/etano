<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_settings.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$query="SELECT `config_option`,`config_value`,`config_diz`,`option_type`,`choices`,`fk_module_code` FROM `{$dbtable_prefix}site_options3` WHERE `per_user`=1 ORDER BY `fk_module_code` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$prefs=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	// we don't sanitize config_diz. Display as is.
	$rsrow['config_value']=sanitize_and_format($rsrow['config_value'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$prefs[$rsrow['fk_module_code']][$rsrow['config_option']]['config_value']=$rsrow['config_value'];
	$prefs[$rsrow['fk_module_code']][$rsrow['config_option']]['config_diz']=$rsrow['config_diz'];
	$prefs[$rsrow['fk_module_code']][$rsrow['config_option']]['option_type']=$rsrow['option_type'];
	if ($rsrow['option_type']==FIELD_SELECT) {
		$prefs[$rsrow['fk_module_code']][$rsrow['config_option']]['choices']=unserialize($rsrow['choices']);
	}
}

$query="SELECT `config_option`,`config_value`,`fk_module_code` FROM `{$dbtable_prefix}user_settings2` WHERE `fk_user_id`=".$_SESSION['user']['user_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['config_value']=sanitize_and_format($rsrow['config_value'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$prefs[$rsrow['fk_module_code']][$rsrow['config_option']]['config_value']=$rsrow['config_value'];
}

$loop=array();
$i=0;
foreach ($prefs as $module_code=>$v) {
	foreach ($v as $config_option=>$kv) {
		if (isset($kv['config_diz']) && !empty($module_code)) {
			$loop[$i]['config_diz']=$kv['config_diz'];
			switch ($kv['option_type']) {

				case FIELD_CHECKBOX:
					$kv['config_value']=($kv['config_value']==1) ? 'checked="checked"' : '';
					$loop[$i]['field']='<input type="checkbox" name="'.$module_code.'_'.$config_option.'" id="'.$module_code.'_'.$config_option.'" value="1" '.$kv['config_value'].' />';
					break;

				case FIELD_TEXTFIELD:
					$loop[$i]['field']='<input type="text" name="'.$module_code.'_'.$config_option.'" id="'.$module_code.'_'.$config_option.'" value="'.$kv['config_value'].'" />';
					break;

				case FIELD_INT:
					$loop[$i]['field']='<input class="number" type="text" name="'.$module_code.'_'.$config_option.'" id="'.$module_code.'_'.$config_option.'" value="'.$kv['config_value'].'" />';
					break;

				case FIELD_TEXTAREA:
					$loop[$i]['field']='<textarea name="'.$module_code.'_'.$config_option.'" id="'.$module_code.'_'.$config_option.'">'.$kv['config_value'].'</textarea>';
					break;

				case FIELD_SELECT:
					$loop[$i]['field']='<select name="'.$module_code.'_'.$config_option.'" id="'.$module_code.'_'.$config_option.'">'.vector2options($kv['choices'],$kv['config_value']).'</select>';
					break;

			}
			++$i;
		}
	}
}

$output['gmtime']=gmdate('F d, Y, h:i:s A');
$tpl->set_file('content','my_settings.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='My Settings';
$tplvars['page_title']='My Settings';
$tplvars['page']='my_settings';
$tplvars['css']='my_settings.css';
if (is_file('my_settings_left.php')) {
	include 'my_settings_left.php';
}
include 'frame.php';

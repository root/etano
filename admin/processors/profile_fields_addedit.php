<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_fields_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/profile_fields.inc.php';
require _BASEPATH_.'/includes/classes/services_json.class.php';
allow_dept(DEPT_ADMIN);

require_once _BASEPATH_.'/includes/interfaces/iprofile_field.class.php';
if ($dh=opendir(_BASEPATH_.'/includes/classes/fields')) {
	while (($file=readdir($dh)) !== false) {
		if (substr($file,-3)=='php') {
			require_once _BASEPATH_.'/includes/classes/fields/'.$file;
		}
	}
	closedir($dh);
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='profile_fields.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($profile_fields_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$profile_fields_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	$input['label']=sanitize_and_format_gpc($_POST,'label',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['search_label']=sanitize_and_format_gpc($_POST,'search_label',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['help_text']=sanitize_and_format_gpc($_POST,'help_text',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

// check for input errors
	if (empty($input['label'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the label for this field!';
		$input['error_label']='red_border';
	}
	if (!empty($input['searchable']) && empty($input['search_label'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the label used on search forms for this field!';
		$input['error_search_label']='red_border';
	}
	if (empty($input['fk_pcat_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a category for this field!';
		$input['error_fk_pcat_id']='red_border';
	}


	$new_field=null;
	if (class_exists($input['field_type'])) {
		$new_field=new $input['field_type']();
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid field type received!';
	}

/*
	if (($input['field_type']==FIELD_SELECT || $input['field_type']==FIELD_CHECKBOX_LARGE) && (empty($input['accepted_values']) || $input['accepted_values']=='||')) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='You need to add at least one option for this type of field!';
		$input['error_accepted_values']='red_border';
	}
*/

	$default_skin_code=get_default_skin_code();
	if (!$error) {
		if (!empty($input['pfield_id'])) {
			$error=$new_field->admin_processor();
			if (!$error) {
				unset($input['dbfield'],$profile_fields_default['defaults']['order_num']);
				$query="UPDATE `{$dbtable_prefix}profile_fields2` SET ";
				foreach ($profile_fields_default['defaults'] as $k=>$v) {
					if (isset($input[$k])) {
						$query.="`$k`='".$input[$k]."',";
					}
				}
				$query=substr($query,0,-1);
				$query.=" WHERE `pfield_id`=".$input['pfield_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="REPLACE INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['label']."',`fk_lk_id`=".$input['fk_lk_id_label'].",`skin`='$default_skin_code'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="REPLACE INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['search_label']."',`fk_lk_id`=".$input['fk_lk_id_search'].",`skin`='$default_skin_code'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="REPLACE INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['help_text']."',`fk_lk_id`=".$input['fk_lk_id_help'].",`skin`='$default_skin_code'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Field changed.';
			}
		} else {
			unset($input['pfield_id']);
			$dbfield_index=get_site_option('dbfield_index','core');
			$input['dbfield']='f'.$dbfield_index;
			// language keys&strings for this field
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Label for ".$input['dbfield']." field',`lk_use`=".LK_FIELD;
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_label']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Search label for ".$input['dbfield']." field',`lk_use`=".LK_FIELD;
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_search']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTAREA.",`lk_diz`='Help text for ".$input['dbfield']." field',`lk_use`=".LK_FIELD;
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_help']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` (`lang_value`,`fk_lk_id`,`skin`) VALUES ('".$input['label']."','".$input['fk_lk_id_label']."','$default_skin_code'),
				('".$input['search_label']."','".$input['fk_lk_id_search']."','$default_skin_code'),
				('".$input['help_text']."','".$input['fk_lk_id_help']."','$default_skin_code')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			$query="SELECT max(`order_num`) FROM `{$dbtable_prefix}profile_fields2`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['order_num']=mysql_result($res,0,0)+1;
			$query="INSERT INTO `{$dbtable_prefix}profile_fields2` SET ";
			foreach ($profile_fields_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['pfield_id']=mysql_insert_id();
			// execute field related processor stuff
			$error=$new_field->admin_processor();
			if (!$error) {
				// save any custom configuration in profile_fields
				$query="UPDATE `{$dbtable_prefix}profile_fields2` SET `custom_config`='".$input['custom_config']."' WHERE `pfield_id`=".$input['pfield_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				// create the field in the user_profiles table
				$temp=$new_field->query_create($input['dbfield']);
				if (!empty($temp)) {
					$query="ALTER TABLE `{$dbtable_prefix}user_profiles` $temp";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					set_site_option('dbfield_index','core',$dbfield_index+1);
				}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Field added.';
			}
		}
//		regenerate_langstrings_array();
//		regenerate_fields_array();

	} else {
		$nextpage='profile_fields_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/profile_fields_addedit.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/profile_fields.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/profile_fields.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($profile_fields_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$profile_fields_default['defaults'][$k]);
	}

	$input['label']=sanitize_and_format_gpc($_POST,'label',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['search_label']=sanitize_and_format_gpc($_POST,'search_label',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['help_text']=sanitize_and_format_gpc($_POST,'help_text',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');

	if ($input['html_type']==_HTML_DATE_) {
		$input['year_start']=sanitize_and_format_gpc($_POST,'year_start',TYPE_INT,0,0);
		$input['year_end']=sanitize_and_format_gpc($_POST,'year_end',TYPE_INT,0,0);
		$input['def_start']=sanitize_and_format_gpc($_POST,'def_start',TYPE_INT,0,0);
		$input['def_end']=sanitize_and_format_gpc($_POST,'def_end',TYPE_INT,0,0);
	}

	// other formatting
	$input['label']=ucfirst(strtolower($input['label']));
	$input['search_label']=ucfirst(strtolower($input['search_label']));
	$input['help_text']=ucfirst(strtolower($input['help_text']));

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

	if (($input['html_type']==_HTML_SELECT_ || $input['html_type']==_HTML_CHECKBOX_LARGE_) && (empty($input['accepted_values']) || $input['accepted_values']=='||')) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='You need to add at least one option for this type of field!';
		$input['error_accepted_values']='red_border';
	}

	if (empty($input['fk_pcat_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please select a category for this field!';
		$input['error_fk_pcat_id']='red_border';
	}

	if ($input['html_type']==_HTML_SELECT_) {
		if ($input['default_value']!=='') {
			$input['default_value']='|'.$input['default_value'].'|';
		}
	} elseif ($input['html_type']==_HTML_CHECKBOX_LARGE_) {
		if (!empty($input['default_value'])) {
			unset($input['default_value']['']);
			$input['default_value']='|'.join('|',array_keys($input['default_value'])).'|';
		} else {
			$input['default_value']='';
		}
	} elseif ($input['html_type']==_HTML_DATE_) {
		if (!$error && empty($input['year_start']) || empty($input['year_end']) || $input['year_start']<1000 || $input['year_end']<1000) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter the start and end years!';
			$input['error_accepted_values']='red_border';
		}
		if (!$error && empty($input['def_start']) || empty($input['def_end'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter the default values for the search interval (age)!';
			$input['error_default_value']='red_border';
		}
		$min=min($input['year_start'],$input['year_end']);
		$max=max($input['year_start'],$input['year_end']);
		$input['year_start']=$min;
		$input['year_end']=$max;
		$min=min($input['def_start'],$input['def_end']);
		$max=max($input['def_start'],$input['def_end']);
		$input['def_start']=$min;
		$input['def_end']=$max;

		$input['accepted_values']='|'.$input['year_start'].'|'.$input['year_end'].'|';
		$input['default_value']='|'.$input['def_start'].'|'.$input['def_end'].'|';
		$now=date('Y');
		if (!$error && $input['def_start']<$now-$input['year_end'] || $input['def_start']>$now-$input['year_start']) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=sprintf('The minimum default search value is not within the years range. Allowed values are between %s and %s.',$now-$input['year_end'],$now-$input['year_start']);
			$input['error_default_value']='red_border';
		}
		if (!$error && $input['def_end']<$now-$input['year_end'] || $input['def_end']>$now-$input['year_start']) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=sprintf('The maximum default search value is not within the years range. Allowed values are between %s and %s.',$now-$input['year_end'],$now-$input['year_start']);
			$input['error_default_value']='red_border';
		}
	} elseif ($input['html_type']==_HTML_LOCATION_) {
		$input['default_value']='|'.$input['default_value'].'|';
	}

	if (!$error) {
		if (!empty($input['pfield_id'])) {
			unset($input['dbfield'],$profile_fields_default['defaults']['order_num']);
			$query="UPDATE `{$dbtable_prefix}profile_fields` SET ";
			foreach ($profile_fields_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `pfield_id`='".$input['pfield_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['fk_lk_id_label']."' AND `skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['label']."' WHERE `fk_lk_id`='".$input['fk_lk_id_label']."' AND `skin`='"._DEFAULT_SKIN_."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['label']."',`fk_lk_id`='".$input['fk_lk_id_label']."',`skin`='"._DEFAULT_SKIN_."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['fk_lk_id_search']."' AND `skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['search_label']."' WHERE `fk_lk_id`='".$input['fk_lk_id_search']."' AND `skin`='"._DEFAULT_SKIN_."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['search_label']."',`fk_lk_id`='".$input['fk_lk_id_search']."',`skin`='"._DEFAULT_SKIN_."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['fk_lk_id_help']."' AND `skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['help_text']."' WHERE `fk_lk_id`='".$input['fk_lk_id_help']."' AND `skin`='"._DEFAULT_SKIN_."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['help_text']."',`fk_lk_id`='".$input['fk_lk_id_help']."',`skin`='"._DEFAULT_SKIN_."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			regenerate_fields_array();
			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Field changed.';
		} else {
			$dbfield_index=get_site_option('dbfield_index','core');
			$input['dbfield']='f'.$dbfield_index;
// language keys&strings for this field
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`="._HTML_TEXTFIELD_.",`lk_diz`='Label for ".$input['dbfield']." field'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_label']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`="._HTML_TEXTFIELD_.",`lk_diz`='Search label for ".$input['dbfield']." field'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_search']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`="._HTML_TEXTAREA_.",`lk_diz`='Help text for ".$input['dbfield']." field'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_help']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['label']."',`fk_lk_id`='".$input['fk_lk_id_label']."',`skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['search_label']."',`fk_lk_id`='".$input['fk_lk_id_search']."',`skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['help_text']."',`fk_lk_id`='".$input['fk_lk_id_help']."',`skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			$query="SELECT max(`order_num`) FROM `{$dbtable_prefix}profile_fields`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['order_num']=mysql_result($res,0,0)+1;
			$query="INSERT INTO `{$dbtable_prefix}profile_fields` SET ";
			foreach ($profile_fields_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if ($input['html_type']!=_HTML_LOCATION_) {
				$query="ALTER TABLE `{$dbtable_prefix}user_profiles` ADD `".$input['dbfield'].'` '.$field_dbtypes[$input['html_type']];
			} else {
				$query="ALTER TABLE `{$dbtable_prefix}user_profiles` ADD `".$input['dbfield'].'_country` int(3) not null default 0, ADD `'.$input['dbfield'].'_state` int(10) not null default 0, ADD `'.$input['dbfield'].'_city` int(10) not null default 0, ADD `'.$input['dbfield'].'_zip` varchar(10) not null default \'\'';
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			set_site_option('dbfield_index','core',$dbfield_index+1);

			regenerate_fields_array();
			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Field added.';
		}
	} else {
		$nextpage='admin/profile_fields_addedit.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
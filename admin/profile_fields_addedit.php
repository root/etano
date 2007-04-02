<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_fields_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/profile_fields.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$profile_fields=$profile_fields_default['defaults'];
$accepted_values=array();
if (isset($_SESSION['topass']['input'])) {
	$profile_fields=$_SESSION['topass']['input'];
	$accepted_values=explode('|',substr($profile_fields['accepted_values'],1,-1));
} elseif (isset($_GET['pfield_id']) && !empty($_GET['pfield_id'])) {
	$pfield_id=(int)$_GET['pfield_id'];
	$query="SELECT * FROM `{$dbtable_prefix}profile_fields` WHERE `pfield_id`='$pfield_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_fields=mysql_fetch_assoc($res);
	}
	$profile_fields['label']='';
	$profile_fields['search_label']='';
	$profile_fields['help_text']='';
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id`='".$profile_fields['fk_lk_id_label']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_fields['label']=mysql_result($res,0,0);
	}
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id`='".$profile_fields['fk_lk_id_search']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_fields['search_label']=mysql_result($res,0,0);
	}
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id`='".$profile_fields['fk_lk_id_help']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_fields['help_text']=mysql_result($res,0,0);
	}
	$profile_fields=sanitize_and_format($profile_fields,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$accepted_values=explode('|',substr($profile_fields['accepted_values'],1,-1));
} elseif (isset($_GET['html_type']) && !empty($_GET['html_type'])) {
	$profile_fields['html_type']=(int)$_GET['html_type'];
}

switch ($profile_fields['html_type']) {

	case HTML_TEXTFIELD:
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='invisible';
		$profile_fields['search_type']='';
		$profile_fields['row_accval_select']=false;
		$profile_fields['row_accval_checkbox']=false;
		break;

	case HTML_TEXTAREA:
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='invisible';
		$profile_fields['search_type']='';
		$profile_fields['row_accval_select']=false;
		$profile_fields['row_accval_checkbox']=false;
		break;

	case HTML_SELECT:
		if (!empty($accepted_values)) {
			$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id` IN ('".join("','",$accepted_values)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$accepted_values=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$accepted_values[$rsrow['fk_lk_id']]=$rsrow['lang_value'];
			}
		}
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='visible';
		$profile_fields['search_type']=vector2options($accepted_htmltype,$profile_fields['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_DATE,HTML_LOCATION));
		// show the accepted values for selects and checkboxes fields
		$profile_fields['row_accval_selcheck']=true;
		// revert $accepted_values values to db original and add slashes
		$profile_fields['acc_vals_jsarrays']=vector2jsarrays(sanitize_and_format($accepted_values,TYPE_STRING,FORMAT_ADDSLASH | FORMAT_TEXT2HTML));
		if (!empty($profile_fields['default_value']) && $profile_fields['default_value']!='||') {
			$profile_fields['default_value_jsarr']=str_replace('|',"','",substr($profile_fields['default_value'],1,-1));
		}
		if (!empty($profile_fields['default_search']) && $profile_fields['default_search']!='||') {
			$profile_fields['default_search_jsarr']=str_replace('|',"','",substr($profile_fields['default_search'],1,-1));
		}
		break;

	case HTML_CHECKBOX_LARGE:
		if (!empty($accepted_values)) {
			$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id` IN ('".join("','",$accepted_values)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$accepted_values=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$accepted_values[$rsrow['fk_lk_id']]=$rsrow['lang_value'];
			}
		}
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='visible';
		$profile_fields['search_type']=vector2options($accepted_htmltype,$profile_fields['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_DATE,HTML_LOCATION,HTML_INTERVAL));
		$profile_fields['row_accval_selcheck']=true;
		// revert $accepted_values values to db original and add slashes
		$profile_fields['acc_vals_jsarrays']=vector2jsarrays(sanitize_and_format($accepted_values,TYPE_STRING,FORMAT_ADDSLASH | FORMAT_TEXT2HTML));
		if (!empty($profile_fields['default_value']) && $profile_fields['default_value']!='||') {
			$profile_fields['default_value_jsarr']=str_replace('|',"','",substr($profile_fields['default_value'],1,-1));
		}
		if (!empty($profile_fields['default_search']) && $profile_fields['default_search']!='||') {
			$profile_fields['default_search_jsarr']=str_replace('|',"','",substr($profile_fields['default_search'],1,-1));
		}
		break;

	case HTML_DATE:
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='visible';
		$profile_fields['search_type']=vector2options($accepted_htmltype,$profile_fields['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_SELECT,HTML_CHECKBOX_LARGE,HTML_LOCATION));
		$profile_fields['row_accval_date']=true;
		$profile_fields['year_start']=(isset($accepted_values[0]) && !empty($accepted_values[0])) ? $accepted_values[0] : 0;
		$profile_fields['year_end']=isset($accepted_values[1]) ? $accepted_values[1] : 0;
		$default_value=explode('|',substr($profile_fields['default_value'],1,-1));
		$profile_fields['def_start']=(isset($default_value[0]) && !empty($default_value[0])) ? $default_value[0] : 0;
		$profile_fields['def_end']=isset($default_value[1]) ? $default_value[1] : 0;
		break;

	case HTML_LOCATION:
		$profile_fields['default_value']=substr($profile_fields['default_value'],1,-1);
		$profile_fields['row_searchable']=true;
		$profile_fields['row_st']='visible';
		$profile_fields['search_type']=vector2options($accepted_htmltype,$profile_fields['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_SELECT,HTML_CHECKBOX_LARGE,HTML_DATE));
		$profile_fields['row_accval_location']=true;
		$profile_fields['default_value']=dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$profile_fields['default_value']);
		break;

}

$profile_fields['htmltype_text']=$accepted_htmltype[$profile_fields['html_type']];
$profile_fields['searchable']=!empty($profile_fields['searchable']) ? 'checked="checked"' : '';
$profile_fields['at_registration']=!empty($profile_fields['at_registration']) ? 'checked="checked"' : '';
$profile_fields['required']=!empty($profile_fields['required']) ? 'checked="checked"' : '';
$profile_fields['fk_pcat_id']=dbtable2options("`{$dbtable_prefix}profile_categories` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_pcat`=b.`fk_lk_id` AND b.`skin`='"._DEFAULT_SKIN_."')",'a.`pcat_id`','b.`lang_value`','a.`pcat_id`',$profile_fields['fk_pcat_id']);
$profile_fields['editable']=!empty($profile_fields['editable']) ? 'checked="checked"' : '';
$profile_fields['visible']=!empty($profile_fields['visible']) ? 'checked="checked"' : '';
//$profile_fields['access_level']=vector2options($accepted_memberships,$profile_fields['access_level']);

$tpl->set_file('content','profile_fields_addedit.html');
$tpl->set_var('profile_fields',$profile_fields);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['ob'])) {
	$tpl->set_var('ob',$_GET['ob']);
}
if (isset($_GET['od'])) {
	$tpl->set_var('od',$_GET['od']);
}
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Profile Fields Management';
include 'frame.php';


function vector2jsarrays($myarray=array()) {
	$myreturn="var accvals=Array(";
	if (is_array($myarray) && !empty($myarray)) {
		$myreturn.="'".join("','",$myarray)."'";
	}
	$myreturn.=");\n";
	$myreturn.="\tvar accval_lks=Array(";
	if (is_array($myarray) && !empty($myarray)) {
		$myreturn.="'".join("','",array_keys($myarray))."'";
	}
	$myreturn.=");\n";
	return $myreturn;
}
?>
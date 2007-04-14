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
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/profile_fields.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$profile_fields_default['defaults'];
// we force here 'editable' and 'visible' regardless of the default values
$output['editable']=1;
$output['visible']=1;
$accepted_values=array();
$default_skin_code=get_default_skin_code();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$accepted_values=explode('|',substr($output['accepted_values'],1,-1));
} elseif (isset($_GET['pfield_id']) && !empty($_GET['pfield_id'])) {
	$pfield_id=(int)$_GET['pfield_id'];
	$query="SELECT * FROM `{$dbtable_prefix}profile_fields` WHERE `pfield_id`='$pfield_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
	$output['label']='';
	$output['search_label']='';
	$output['help_text']='';
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id`='".$output['fk_lk_id_label']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['label']=mysql_result($res,0,0);
	}
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id`='".$output['fk_lk_id_search']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['search_label']=mysql_result($res,0,0);
	}
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id`='".$output['fk_lk_id_help']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['help_text']=mysql_result($res,0,0);
	}
	$output=sanitize_and_format($output,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$accepted_values=explode('|',substr($output['accepted_values'],1,-1));
} elseif (isset($_GET['html_type']) && !empty($_GET['html_type'])) {
	$output['html_type']=(int)$_GET['html_type'];
}

switch ($output['html_type']) {

	case HTML_TEXTFIELD:
		$output['row_searchable']=true;
		$output['row_st']='invisible';
		$output['search_type']='';
		$output['row_accval_select']=false;
		$output['row_accval_checkbox']=false;
		break;

	case HTML_TEXTAREA:
		$output['row_searchable']=true;
		$output['row_st']='invisible';
		$output['search_type']='';
		$output['row_accval_select']=false;
		$output['row_accval_checkbox']=false;
		break;

	case HTML_SELECT:
		if (!empty($accepted_values)) {
			$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id` IN ('".join("','",$accepted_values)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$accepted_values=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$accepted_values[$rsrow['fk_lk_id']]=$rsrow['lang_value'];
			}
		}
		$output['row_searchable']=true;
		$output['row_st']='visible';
		$output['search_type']=vector2options($accepted_htmltype,$output['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_DATE,HTML_LOCATION));
		// show the accepted values for selects and checkboxes fields
		$output['row_accval_selcheck']=true;
		// revert $accepted_values values to db original and add slashes
		$output['acc_vals_jsarrays']=vector2jsarrays(sanitize_and_format($accepted_values,TYPE_STRING,FORMAT_ADDSLASH | FORMAT_TEXT2HTML));
		if (!empty($output['default_value']) && $output['default_value']!='||') {
			$output['default_value_jsarr']="'".str_replace('|',"','",substr($output['default_value'],1,-1))."'";
		}
		if (!empty($output['default_search']) && $output['default_search']!='||') {
			$output['default_search_jsarr']="'".str_replace('|',"','",substr($output['default_search'],1,-1))."'";
		}
		break;

	case HTML_CHECKBOX_LARGE:
		if (!empty($accepted_values)) {
			$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id` IN ('".join("','",$accepted_values)."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$accepted_values=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$accepted_values[$rsrow['fk_lk_id']]=$rsrow['lang_value'];
			}
		}
		$output['row_searchable']=true;
		$output['row_st']='visible';
		$output['search_type']=vector2options($accepted_htmltype,$output['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_DATE,HTML_LOCATION,HTML_RANGE));
		$output['row_accval_selcheck']=true;
		// revert $accepted_values values to db original and add slashes
		$output['acc_vals_jsarrays']=vector2jsarrays(sanitize_and_format($accepted_values,TYPE_STRING,FORMAT_ADDSLASH | FORMAT_TEXT2HTML));
		if (!empty($output['default_value']) && $output['default_value']!='||') {
			$output['default_value_jsarr']="'".str_replace('|',"','",substr($output['default_value'],1,-1))."'";
		}
		if (!empty($output['default_search']) && $output['default_search']!='||') {
			$output['default_search_jsarr']="'".str_replace('|',"','",substr($output['default_search'],1,-1))."'";
		}
		break;

	case HTML_DATE:
		$output['row_searchable']=true;
		$output['row_st']='visible';
		$output['search_type']=vector2options($accepted_htmltype,$output['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_SELECT,HTML_CHECKBOX_LARGE,HTML_LOCATION,HTML_DATE));
		$output['row_accval_date']=true;
		$output['year_start']=(isset($accepted_values[0]) && !empty($accepted_values[0])) ? $accepted_values[0] : 0;
		$output['year_end']=isset($accepted_values[1]) ? $accepted_values[1] : 0;
		$default_search=explode('|',substr($output['default_search'],1,-1));
		$output['def_start']=(isset($default_search[0]) && !empty($default_search[0])) ? $default_search[0] : 0;
		$output['def_end']=isset($default_search[1]) ? $default_search[1] : 0;
		break;

	case HTML_LOCATION:
		$output['default_value']=substr($output['default_value'],1,-1);
		$output['row_searchable']=true;
		$output['row_st']='visible';
		$output['search_type']=vector2options($accepted_htmltype,$output['search_type'],array(HTML_TEXTFIELD,HTML_TEXTAREA,HTML_SELECT,HTML_CHECKBOX_LARGE,HTML_DATE,HTML_RANGE));
		$output['row_accval_location']=true;
		$output['default_value']=dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output['default_value']);
		break;

}

$output['htmltype_text']=$accepted_htmltype[$output['html_type']];
$output['searchable']=!empty($output['searchable']) ? 'checked="checked"' : '';
$output['for_basic']=!empty($output['for_basic']) ? 'checked="checked"' : '';
$output['at_registration']=!empty($output['at_registration']) ? 'checked="checked"' : '';
$output['required']=!empty($output['required']) ? 'checked="checked"' : '';
$output['fk_pcat_id']=dbtable2options("`{$dbtable_prefix}profile_categories` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_pcat`=b.`fk_lk_id` AND b.`skin`='$default_skin_code')",'a.`pcat_id`','b.`lang_value`','a.`pcat_id`',$output['fk_pcat_id']);
$output['editable']=!empty($output['editable']) ? 'checked="checked"' : '';
$output['visible']=!empty($output['visible']) ? 'checked="checked"' : '';
//$output['access_level']=vector2options($accepted_memberships,$output['access_level']);
$output['default_skin']=get_default_skin_name();

$tpl->set_file('content','profile_fields_addedit.html');
$tpl->set_var('output',$output);
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
$tplvars['css']='profile_fields_addedit.css';
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
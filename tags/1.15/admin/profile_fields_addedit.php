<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile_fields_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/admin_functions.inc.php';
require _BASEPATH_.'/includes/tables/profile_fields.inc.php';
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

$tpl=new phemplate('skin/','remove_nonjs');

$output=$profile_fields_default['defaults'];
// we force here 'editable' and 'visible' regardless of the default values
$output['editable']=1;
$output['visible']=1;
$accepted_values=array();
$default_skin_code=get_default_skin_code();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];

	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['pfield_id'])) {
	$pfield_id=(int)$_GET['pfield_id'];
	$query="SELECT `pfield_id`,`fk_lk_id_label`,`field_type`,`searchable`,`search_type`,`for_basic`,`fk_lk_id_search`,`at_registration`,`reg_page`,`required`,`editable`,`visible`,`dbfield`,`fk_lk_id_help`,`fk_pcat_id`,`custom_config`,`fn_on_change`,`order_num` FROM `{$dbtable_prefix}profile_fields2` WHERE `pfield_id`=$pfield_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$temp=unserialize($output['custom_config']);
		unset($output['custom_config']);
		if (is_array($temp)) {
			$output=array_merge($output,$temp);
		}
	}
	$output['label']='';
	$output['search_label']='';
	$output['help_text']='';
	$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$default_skin_code' AND `fk_lk_id` IN (".$output['fk_lk_id_label'].','.$output['fk_lk_id_search'].','.$output['fk_lk_id_help'].')';
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['fk_lk_id']==$output['fk_lk_id_label']) {
			$output['label']=$rsrow['lang_value'];
		} elseif ($rsrow['fk_lk_id']==$output['fk_lk_id_search']) {
			$output['search_label']=$rsrow['lang_value'];
		} elseif ($rsrow['fk_lk_id']==$output['fk_lk_id_help']) {
			$output['help_text']=$rsrow['lang_value'];
		}
	}
	$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
} elseif (!empty($_GET['field_type'])) {
	$output['field_type']=$_GET['field_type'];
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$new_field=null;
if (class_exists($output['field_type'])) {
	$new_field=new $output['field_type'];
}

if (!empty($new_field->allowed_search_types)) {
	$output['row_searchable']=true;
	$temp=array();
	for ($i=0;isset($new_field->allowed_search_types[$i]);++$i) {
		$temp[$new_field->allowed_search_types[$i]]=$accepted_fieldtype['search'][$new_field->allowed_search_types[$i]];
	}
	// keep $search_type because it is refered from $field->edit_admin()
	$search_type=!empty($output['search_type']) ? $output['search_type'] : $new_field->allowed_search_types[0];
	$output['search_type']=vector2options($temp,$output['search_type']);
	// we initialize the search field so we can ask for its admin config in search mode.
	$search_field=null;
	if (class_exists($search_type)) {
		$search_field=new $search_type(array(),true);
	}
	if (!empty($search_field)) {
		$output['custom_config_search']=$search_field->edit_admin();
	}
}

$output['fieldtype_text']=$accepted_fieldtype['direct'][$output['field_type']];
$output['searchable']=!empty($output['searchable']) ? 'checked="checked"' : '';
$output['for_basic']=!empty($output['for_basic']) ? 'checked="checked"' : '';
$output['at_registration']=!empty($output['at_registration']) ? 'checked="checked"' : '';
$output['required']=!empty($output['required']) ? 'checked="checked"' : '';
$output['fk_pcat_id']=dbtable2options("`{$dbtable_prefix}profile_categories` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_pcat`=b.`fk_lk_id` AND b.`skin`='$default_skin_code')",'a.`pcat_id`','b.`lang_value`','a.`pcat_id`',$output['fk_pcat_id']);
$output['editable']=!empty($output['editable']) ? 'checked="checked"' : '';
$output['visible']=!empty($output['visible']) ? 'checked="checked"' : '';
$output['custom_config_direct']=$new_field->edit_admin();
$output['default_skin']=get_default_skin_name();

$tpl->set_file('content','profile_fields_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Profile Fields Management';
$tplvars['css']='profile_fields_addedit.css';
$tplvars['page']='profile_fields_addedit';
include 'frame.php';

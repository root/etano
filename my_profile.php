<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$config=get_site_option(array('bbcode_profile'),'core');

$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
	// set all the fields to their real (readable) values
	foreach ($_pfields as $field_id=>$field) {
		if ($field['visible']) {
			if ($field['field_type']==FIELD_TEXTFIELD) {
				$output[$field['dbfield']]=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			} elseif ($field['field_type']==FIELD_TEXTAREA) {
				$output[$field['dbfield']]=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				if ($config['bbcode_profile']) {
					$output[$field['dbfield']]=bbcode2html($output[$field['dbfield']]);
				}
			} elseif ($field['field_type']==FIELD_SELECT) {
				// if we sanitize here " will be rendered as &quot; which is not what we want
	//			$output[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$output[$field['dbfield']]],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				$output[$field['dbfield']]=$field['accepted_values'][$output[$field['dbfield']]];
			} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
				$output[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$output[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			} elseif ($field['field_type']==FIELD_INT || $field['field_type']==FIELD_FLOAT) {
	//			$output[$field['dbfield']]=$output[$field['dbfield']];
			} elseif ($field['field_type']==FIELD_DATE) {
	//			$output[$field['dbfield']]=$output[$field['dbfield']];
			} elseif ($field['field_type']==FIELD_LOCATION) {
				$output[$field['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$output[$field['dbfield'].'_country'],'-');
				if (!empty($output[$field['dbfield'].'_state'])) {
					$output[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$output[$field['dbfield'].'_state'],'-');
				}
				if (!empty($output[$field['dbfield'].'_city'])) {
					$output[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$output[$field['dbfield'].'_city'],'-');
				}
			}
		} else {
			unset($output[$field['dbfield']]);
		}
	}
}

$categs=array();
$i=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$fields=array();
	for ($j=0;isset($pcat['fields'][$j]);++$j) {
		if ($_pfields[$pcat['fields'][$j]]['visible']) {
			$fields[]=array('label'=>$_pfields[$pcat['fields'][$j]]['label'],'field'=>isset($output[$_pfields[$pcat['fields'][$j]]['dbfield']]) ? $output[$_pfields[$pcat['fields'][$j]]['dbfield']] : '?');
		}
	}
	$categs[$i]['pcat_name']=$pcat['pcat_name'];
	$categs[$i]['pcat_id']=$pcat_id;
	$categs[$i]['fields']=$fields;
	++$i;
}

$output['return2me']='my_profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	if (!empty($edit_comment)) {
		$_SERVER['QUERY_STRING']=str_replace('&edit_comment='.$edit_comment,'',$_SERVER['QUERY_STRING']);
	}
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_profile.html');
$tpl->set_loop('categs',$categs);
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
unset($categs);

$tplvars['title']='My Profile';
$tplvars['page_title']='My Profile';
$tplvars['page']='my_profile';
$tplvars['css']='my_profile.css';
if (is_file('my_profile_left.php')) {
	include 'my_profile_left.php';
}
unset($page_last_modified_time);	// we want everything fresh on this page.
include 'frame2.php';

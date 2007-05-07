<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(-1);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$config=get_site_option(array('bbcode_profile'),'core');

$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
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

if (empty($output['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$output['_photo'])) {
	unset($output['_photo']);
}

$user_photos=array();
$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	while (count($user_photos)<3 && $rsrow=mysql_fetch_assoc($res)) {
		if (is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$user_photos[]=$rsrow;
		}
	}
	$user_photos[0]['class']='first';
	$output['num_photos']=mysql_num_rows($res);
}

$categs=array();
$i=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$fields=array();
	for ($j=0;isset($pcat['fields'][$j]);++$j) {
		$fields[$j]['label']=$_pfields[$pcat['fields'][$j]]['label'];
		$fields[$j]['field']=$output[$_pfields[$pcat['fields'][$j]]['dbfield']];
	}
	$categs[$i]['pcat_name']=$pcat['pcat_name'];
	$categs[$i]['pcat_id']=$pcat_id;
	$categs[$i]['fields']=$fields;
	++$i;
}

$output['pic_width']=get_site_option('pic_width','core_photo');

$output['return']='my_profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','my_profile.html');
$tpl->set_loop('user_photos',$user_photos);
$tpl->set_loop('categs',$categs);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
unset($categs);

$tplvars['title']='My Profile';
$tplvars['page_title']='My Profile';
$tplvars['page']='my_profile';
$tplvars['css']='my_profile.css';
if (is_file('my_profile_left.php')) {
	include 'my_profile_left.php';
}
include 'frame.php';
?>
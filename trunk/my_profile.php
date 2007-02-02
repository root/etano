<?php
/******************************************************************************
newdsb
===============================================================================
File:                       profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/fields.inc.php';
require_once 'includes/classes/user_cache.class.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(2);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$uid=(string)$_SESSION['user']['user_id'];

$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='$uid'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$profile=mysql_fetch_assoc($res);
// set all the fields to their real (readable) values
foreach ($_pfields as $field_id=>$field) {
	if ($field['visible']) {
		if ($field['html_type']==_HTML_TEXTFIELD_ || $field['html_type']==_HTML_TEXTAREA_) {
			$profile[$field['dbfield']]=sanitize_and_format($profile[$field['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		} elseif ($field['html_type']==_HTML_SELECT_) {
			$profile[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$profile[$field['dbfield']]],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		} elseif ($field['html_type']==_HTML_CHECKBOX_LARGE_) {
			$profile[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$profile[$field['dbfield']]),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		} elseif ($field['html_type']==_HTML_INT_ || $field['html_type']==_HTML_FLOAT_) {
//			$profile[$field['dbfield']]=$profile[$field['dbfield']];
		} elseif ($field['html_type']==_HTML_DATE_) {
//			$profile[$field['dbfield']]=$profile[$field['dbfield']];
		} elseif ($field['html_type']==_HTML_LOCATION_) {
			$profile[$field['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$profile[$field['dbfield'].'_country'],'-');
			if (!empty($profile[$field['dbfield'].'_state'])) {
				$profile[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$profile[$field['dbfield'].'_state'],'-');
			}
			if (!empty($profile[$field['dbfield'].'_city'])) {
				$profile[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$profile[$field['dbfield'].'_city'],'-');
			}
		}
	} else {
		unset($profile[$field['dbfield']]);
	}
}

$categs=array();
$i=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$fields=array();
	for ($j=0;isset($pcat['fields'][$j]);++$j) {
		$fields[$j]['label']=$_pfields[$pcat['fields'][$j]]['label'];
		$fields[$j]['field']=$profile[$_pfields[$pcat['fields'][$j]]['dbfield']];
	}
	$categs[$i]['pcat_name']=$pcat['pcat_name'];
	$categs[$i]['pcat_id']=$pcat_id;
	$categs[$i]['fields']=$fields;
	++$i;
}

$tplvars['pic_width']=get_site_option('pic_width','core_photo');

$tpl->set_file('content','my_profile.html');
$tpl->set_loop('categs',$categs);
$tpl->set_var('profile',$profile);
$tpl->set_var('uid',$uid);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

if (is_file('my_profile_left.php')) {
	include 'my_profile_left.php';
}
$tplvars['title']='Your Profile';
include 'frame.php';
?>
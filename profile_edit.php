<?php
/******************************************************************************
Etano
===============================================================================
File:                       profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$cid=1;
$user_details=array();
if (isset($_SESSION['topass']['input'])) {
	$user_details=$_SESSION['topass']['input'];

	$cid=$user_details['pcat_id'];
	unset($user_details['pcat_id']);
} elseif (!empty($_GET['cid'])) {
	$cid=(int)$_GET['cid'];

	if (!isset($_pcats[$cid])) {
		$cid=1;
	}
	$query='SELECT ';
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		if ($_pfields[$field_id]['editable']) {
			switch ($_pfields[$field_id]['field_type']) {

				case FIELD_DATE:
					$query.="YEAR(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_year`,MONTH(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_month`,DAYOFMONTH(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_day`,";
					break;

				case FIELD_LOCATION:
					$query.="`".$_pfields[$field_id]['dbfield']."_country`,`".$_pfields[$field_id]['dbfield']."_state`,`".$_pfields[$field_id]['dbfield']."_city`,`".$_pfields[$field_id]['dbfield']."_zip`,";
					break;

				default:
					$query.="`".$_pfields[$field_id]['dbfield']."`,";

			}
		}
	}
	$query=substr($query,0,-1);
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user_details=mysql_fetch_assoc($res);
		$user_details=sanitize_and_format($user_details,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

if (empty($user_details)) {
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		switch ($_pfields[$field_id]['field_type']) {

			case FIELD_DATE:
				$user_details[$_pfields[$field_id]['dbfield'].'_month']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_day']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_year']=0;
				break;

			case FIELD_LOCATION:
				$user_details[$_pfields[$field_id]['dbfield'].'_country']=$_pfields[$field_id]['default_value'][0];
				$user_details[$_pfields[$field_id]['dbfield'].'_state']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_city']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_zip']='';
				break;

			default:
				$user_details[$_pfields[$field_id]['dbfield']]='';

		}
	}
}

$config=get_site_option(array('ta_len'),'core');
$tplvars['pcat_name']=$_pcats[$cid]['pcat_name'];
$tplvars['pcat_id']=$cid;
$i=0;
$loop=array();
$js_loop=array();
$js=0;
foreach ($_pcats[$cid]['fields'] as $field_id) {
	$field=$_pfields[$field_id];
	$loop[$i]['label']=$field['label'];
	$loop[$i]['dbfield']=$field['dbfield'];
	$loop[$i]['required']=isset($field['required']) ? true : false;
	if ($loop[$i]['required']) {
		$loop[$i]['class']='required';
		$js_loop[$js]['dbfield']=$field['dbfield'];
		$js_loop[$js]['field_type']=$field['field_type'];
		$js_loop[$js]['i']=$js;
		++$js;
	}
	$loop[$i]['help_text']=$field['help_text'];
	if (isset($user_details['error_'.$field['dbfield']])) {
		$loop[$i]['class_error']=$user_details['error_'.$field['dbfield']];
		unset($user_details['error_'.$field['dbfield']]);
	}
	switch ($field['field_type']) {

		case FIELD_TEXTFIELD:
			$loop[$i]['field']='<input type="text" name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" value="'.(isset($user_details[$field['dbfield']]) ? $user_details[$field['dbfield']] : '').'" tabindex="'.($i+4).'" />';
			break;

		case FIELD_TEXTAREA:
			$loop[$i]['field']='<textarea name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.(isset($user_details[$field['dbfield']]) ? $user_details[$field['dbfield']] : '').'</textarea>';
			if (!empty($config['ta_len'])) {
				$loop[$i]['field'].='<p class="comment char_counter">Remaining chars: <span id="'.$field['dbfield'].'_chars">'.($config['ta_len']-strlen($user_details[$field['dbfield']])).'</span></p>'; //translate
			}
			break;

		case FIELD_SELECT:
			$loop[$i]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],isset($user_details[$field['dbfield']]) ? $user_details[$field['dbfield']] : 0,array(0)).'</select>';
			break;

		case FIELD_CHECKBOX_LARGE:
			$loop[$i]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],isset($user_details[$field['dbfield']]) ? $user_details[$field['dbfield']] : '',1,true,'tabindex="'.($i+4).'"');
			break;

		case FIELD_DATE:
			$loop[$i]['field']='<select name="'.$field['dbfield'].'_month" id="'.$field['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$user_details[$field['dbfield'].'_month']).'</select>';
			$loop[$i]['field'].='<select name="'.$field['dbfield'].'_day" id="'.$field['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">day</option>'.interval2options(1,31,$user_details[$field['dbfield'].'_day']).'</select>'; // translate
			$loop[$i]['field'].='<select name="'.$field['dbfield'].'_year" id="'.$field['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">year</option>'.interval2options($field['accepted_values'][1],$field['accepted_values'][2],$user_details[$field['dbfield'].'_year'],array(),1,2).'</select>'; // translate
			break;

		case FIELD_LOCATION:
			$country_id=$user_details[$field['dbfield'].'_country'];
			$state_id=$user_details[$field['dbfield'].'_state'];
			$loop[$i]['label']='Country';	//translate this
			$loop[$i]['dbfield']=$field['dbfield'].'_country';
			$loop[$i]['field']='<select class="full_width" name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$user_details[$field['dbfield'].'_country']).'</select>';	// translate this
			$prefered_input='s';
			$num_states=0;
			$num_cities=0;
			if (!empty($country_id)) {
				$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='$country_id'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					list($prefered_input,$num_states)=mysql_fetch_row($res);
				}
			}
			if (!empty($state_id)) {
				$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`='$state_id'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (mysql_num_rows($res)) {
					$num_cities=mysql_result($res,0,0);
				}
			}
			++$i;
			$loop[$i]['label']='State';	//translate this
			$loop[$i]['dbfield']=$field['dbfield'].'_state';
			$loop[$i]['field']='<select class="full_width" name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option>';	// translate this
			if (!empty($country_id) && $prefered_input=='s' && !empty($num_states)) {
				$loop[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$state_id,"`fk_country_id`='$country_id'");
			}
			$loop[$i]['field'].='</select>';
			$loop[$i]['class']=(!empty($country_id) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$i;
			$loop[$i]['label']='City';	//translate this
			$loop[$i]['dbfield']=$field['dbfield'].'_city';
			$loop[$i]['field']='<select class="full_width" name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option>';	// translate this
			if (!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) {
				$loop[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$user_details[$field['dbfield'].'_city'],"`fk_state_id`='$state_id'");
			}
			$loop[$i]['field'].='</select>';
			$loop[$i]['class']=(!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible';
			++$i;
			$loop[$i]['label']='Zip code';	//translate this
			$loop[$i]['dbfield']=$field['dbfield'].'_zip';
			$loop[$i]['field']='<input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" value="'.$user_details[$field['dbfield'].'_zip'].'" tabindex="'.($i+4).'" />';
			$loop[$i]['class']=(!empty($country_id) && $prefered_input=='z') ? 'visible' : 'invisible';
			break;

	}
	++$i;
}

$output['ta_len']=$config['ta_len'];
$tpl->set_file('content','profile_edit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('loop',$loop);
$tpl->set_loop('js_loop',$js_loop);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
$tpl->drop_loop('js_loop');
unset($loop,$js_loop);

$tplvars['title']=sprintf('Edit My %s',$_pcats[$cid]['pcat_name']);
$tplvars['page_title']=sprintf('<a href="'.$tplvars['relative_url'].'my_profile.php">My Profile</a> - %s',$_pcats[$cid]['pcat_name']);
$tplvars['page']='profile_edit';
$tplvars['css']='profile_edit.css';
if (is_file('profile_edit_left.php')) {
	include 'profile_edit_left.php';
}
include 'frame.php';
?>
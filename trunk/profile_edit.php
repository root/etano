<?php
/******************************************************************************
newdsb
===============================================================================
File:                       profile_edit.php
$Revision: 21 $
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
check_login_member(1);

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$cid=1;
$profile=array();
$user_details=array();
if (isset($_SESSION['topass']['input'])) {
	$user_details=$_SESSION['topass']['input'];

	$cid=$user_details['pcat_id'];
	unset($user_details['pcat_id']);
} elseif (isset($_GET['cid']) && !empty($_GET['cid'])) {
	$cid=(int)$_GET['cid'];

	if (!isset($_pcats[$cid])) {
		$cid=1;
	}
	$query='SELECT ';
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		if ($_pfields[$field_id]['editable']) {
			switch ($_pfields[$field_id]['html_type']) {

				case HTML_DATE:
					$query.="YEAR(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_year`,MONTH(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_month`,DAYOFMONTH(`".$_pfields[$field_id]['dbfield']."`) as `".$_pfields[$field_id]['dbfield']."_day`,";
					break;

				case HTML_LOCATION:
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
		$user_details=sanitize_and_format($user_details,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
} else {
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		switch ($_pfields[$field_id]['html_type']) {

			case HTML_DATE:
				$user_details[$_pfields[$field_id]['dbfield'].'_month']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_day']=0;
				$user_details[$_pfields[$field_id]['dbfield'].'_year']=0;
				break;

			case HTML_LOCATION:
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

$tplvars['pcat_name']=$_pcats[$cid]['pcat_name'];
$tplvars['pcat_id']=$cid;
$i=0;
foreach ($_pcats[$cid]['fields'] as $field_id) {
	$profile[$i]['label']=$_pfields[$field_id]['label'];
	$profile[$i]['dbfield']=$_pfields[$field_id]['dbfield'];
	$profile[$i]['required']=isset($_pfields[$field_id]['required']) ? true : false;
	$profile[$i]['help_text']=$_pfields[$field_id]['help_text'];
	if (isset($user_details['error_'.$_pfields[$field_id]['dbfield']])) {
		$profile[$i]['class_error']=$user_details['error_'.$_pfields[$field_id]['dbfield']];
		unset($user_details['error_'.$_pfields[$field_id]['dbfield']]);
	}
	switch ($_pfields[$field_id]['html_type']) {

		case HTML_TEXTFIELD:
			$profile[$i]['field']='<input type="text" name="'.$_pfields[$field_id]['dbfield'].'" id="'.$_pfields[$field_id]['dbfield'].'" value="'.(isset($user_details[$_pfields[$field_id]['dbfield']]) ? $user_details[$_pfields[$field_id]['dbfield']] : '').'" tabindex="'.($i+4).'" />';
			break;

		case HTML_TEXTAREA:
			$profile[$i]['field']='<textarea name="'.$_pfields[$field_id]['dbfield'].'" id="'.$_pfields[$field_id]['dbfield'].'" tabindex="'.($i+4).'">'.(isset($user_details[$_pfields[$field_id]['dbfield']]) ? $user_details[$_pfields[$field_id]['dbfield']] : '').'</textarea>';
			break;

		case HTML_SELECT:
			$profile[$i]['field']='<select name="'.$_pfields[$field_id]['dbfield'].'" id="'.$_pfields[$field_id]['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($_pfields[$field_id]['accepted_values'],isset($user_details[$_pfields[$field_id]['dbfield']]) ? $user_details[$_pfields[$field_id]['dbfield']] : 0,array(0)).'</select>';
			break;

		case HTML_CHECKBOX_LARGE:
			$profile[$i]['field']=vector2checkboxes_str($_pfields[$field_id]['accepted_values'],array(0),$_pfields[$field_id]['dbfield'],isset($user_details[$_pfields[$field_id]['dbfield']]) ? $user_details[$_pfields[$field_id]['dbfield']] : '',1,true,'tabindex="'.($i+4).'"');
			break;

		case HTML_DATE:
			$profile[$i]['field']='<select name="'.$_pfields[$field_id]['dbfield'].'_month" id="'.$_pfields[$field_id]['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$user_details[$_pfields[$field_id]['dbfield'].'_month']).'</select>';
			$profile[$i]['field'].='<select name="'.$_pfields[$field_id]['dbfield'].'_day" id="'.$_pfields[$field_id]['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">day</option>'.interval2options(1,31,$user_details[$_pfields[$field_id]['dbfield'].'_day']).'</select>'; // translate
			$profile[$i]['field'].='<select name="'.$_pfields[$field_id]['dbfield'].'_year" id="'.$_pfields[$field_id]['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">year</option>'.interval2options($_pfields[$field_id]['accepted_values'][1],$_pfields[$field_id]['accepted_values'][2],$user_details[$_pfields[$field_id]['dbfield'].'_year'],array(),1,2).'</select>'; // translate
			break;

		case HTML_LOCATION:
			$country_id=$user_details[$_pfields[$field_id]['dbfield'].'_country'];
			$state_id=$user_details[$_pfields[$field_id]['dbfield'].'_state'];
			$profile[$i]['label']='Country:';	//translate this
			$profile[$i]['dbfield']=$_pfields[$field_id]['dbfield'].'_country';
			$profile[$i]['field']='<select name="'.$_pfields[$field_id]['dbfield'].'_country" id="'.$_pfields[$field_id]['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)" class="full_width"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$user_details[$_pfields[$field_id]['dbfield'].'_country']).'</select>';	// translate this
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
			$profile[$i]['label']='State:';	//translate this
			$profile[$i]['dbfield']=$_pfields[$field_id]['dbfield'].'_state';
			$profile[$i]['field']='<select name="'.$_pfields[$field_id]['dbfield'].'_state" id="'.$_pfields[$field_id]['dbfield'].'_state" tabindex="'.($i+4).'" class="full_width"><option value="0">Select state</option>';	// translate this
			if (!empty($country_id) && $prefered_input=='s' && !empty($num_states)) {
				$profile[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$user_details[$_pfields[$field_id]['dbfield'].'_state'],"`fk_country_id`='$country_id'");
			}
			$profile[$i]['field'].='</select>';
			$profile[$i]['class']=(!empty($country_id) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$i;
			$profile[$i]['label']='City:';	//translate this
			$profile[$i]['dbfield']=$_pfields[$field_id]['dbfield'].'_city';
			$profile[$i]['field']='<select name="'.$_pfields[$field_id]['dbfield'].'_city" id="'.$_pfields[$field_id]['dbfield'].'_city" tabindex="'.($i+4).'" class="full_width"><option value="0">Select city</option>';	// translate this
			if (!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) {
				$profile[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$user_details[$_pfields[$field_id]['dbfield'].'_city'],"`fk_state_id`='$state_id'");
			}
			$profile[$i]['field'].='</select>';
			$profile[$i]['class']=(!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible';
			++$i;
			$profile[$i]['label']='Zip code:';	//translate this
			$profile[$i]['dbfield']=$_pfields[$field_id]['dbfield'].'_zip';
			$profile[$i]['field']='<input type="text" name="'.$_pfields[$field_id]['dbfield'].'_zip" id="'.$_pfields[$field_id]['dbfield'].'_zip" value="'.$user_details[$_pfields[$field_id]['dbfield'].'_zip'].'" tabindex="'.($i+4).'" />';
			$profile[$i]['class']=(!empty($country_id) && $prefered_input=='z') ? 'visible' : 'invisible';
			break;

	}
	++$i;
}

$tpl->set_file('content','profile_edit.html');
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('profile',$profile);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP);
$tpl->drop_loop('profile');

$tplvars['title']='Member Profile';
include 'frame.php';
?>
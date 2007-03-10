<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_edit.php
$Revision: 29 $
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$loop=array();
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];

	$query='SELECT `fk_user_id`,`_user`';
	foreach ($_pfields as $field_id=>$field) {
		if ($field['editable']) {
			switch ($field['html_type']) {

				case HTML_DATE:
					$query.=",YEAR(`".$field['dbfield']."`) as `".$field['dbfield']."_year`,MONTH(`".$field['dbfield']."`) as `".$field['dbfield']."_month`,DAYOFMONTH(`".$field['dbfield']."`) as `".$field['dbfield']."_day`";
					break;

				case HTML_LOCATION:
					$query.=",`".$field['dbfield']."_country`,`".$field['dbfield']."_state`,`".$field['dbfield']."_city`,`".$field['dbfield']."_zip`";
					break;

				default:
					$query.=",`".$field['dbfield']."`";

			}
		}
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='$uid'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output=sanitize_and_format($output,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
	$output['return2']=rawurldecode($output['return']);
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No user selected';
	redirect2page('admin/cpanel.php',$topass);
}

$c=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$loop[$c]['pcat_name']=$pcat['pcat_name'];
	$cat_content=array();
	for ($i=0;isset($pcat['fields'][$i]);++$i) {
		$field=$_pfields[$pcat['fields'][$i]];
		$cat_content[$i]['label']=$field['label'];
		switch ($field['html_type']) {

			case HTML_TEXTFIELD:
				$cat_content[$i]['field']='<input type="text" name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" value="'.(isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '').'" tabindex="'.($i+4).'" />';
				break;

			case HTML_TEXTAREA:
				$cat_content[$i]['field']='<textarea name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.(isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '').'</textarea>';
				break;

			case HTML_SELECT:
				$cat_content[$i]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : 0,array(0)).'</select>';
				break;

			case HTML_CHECKBOX_LARGE:
				$cat_content[$i]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '',1,true,'tabindex="'.($i+4).'"');
				break;

			case HTML_DATE:
				$cat_content[$i]['field']='<select name="'.$field['dbfield'].'_month" id="'.$field['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$output[$field['dbfield'].'_month']).'</select>';
				$cat_content[$i]['field'].='<select name="'.$field['dbfield'].'_day" id="'.$field['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">'.$_lang[5].'</option>'.interval2options(1,31,$output[$field['dbfield'].'_day']).'</select>';
				$cat_content[$i]['field'].='<select name="'.$field['dbfield'].'_year" id="'.$field['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">'.$_lang[6].'</option>'.interval2options($field['accepted_values'][1],$field['accepted_values'][2],$output[$field['dbfield'].'_year'],array(),1,2).'</select>';
				break;

			case HTML_LOCATION:
				$country_id=$output[$field['dbfield'].'_country'];
				$state_id=$output[$field['dbfield'].'_state'];
				$cat_content[$i]['label']='Country';	//translate this
				$cat_content[$i]['dbfield']=$field['dbfield'].'_country';
				$cat_content[$i]['field']='<select name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)" class="full_width"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output[$field['dbfield'].'_country']).'</select>';	// translate this
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
				$cat_content[$i]['label']='State';	//translate this
				$cat_content[$i]['dbfield']=$field['dbfield'].'_state';
				$cat_content[$i]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" class="full_width"><option value="0">Select state</option>';	// translate this
				if (!empty($country_id) && $prefered_input=='s' && !empty($num_states)) {
					$cat_content[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$output[$field['dbfield'].'_state'],"`fk_country_id`='$country_id'");
				}
				$cat_content[$i]['field'].='</select>';
				$cat_content[$i]['class']=(!empty($country_id) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
				++$i;
				$cat_content[$i]['label']='City';	//translate this
				$cat_content[$i]['dbfield']=$field['dbfield'].'_city';
				$cat_content[$i]['field']='<select name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'" class="full_width"><option value="0">Select city</option>';	// translate this
				if (!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) {
					$cat_content[$i]['field'].=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$output[$field['dbfield'].'_city'],"`fk_state_id`='$state_id'");
				}
				$cat_content[$i]['field'].='</select>';
				$cat_content[$i]['class']=(!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible';
				++$i;
				$cat_content[$i]['label']='Zip code';	//translate this
				$cat_content[$i]['dbfield']=$field['dbfield'].'_zip';
				$cat_content[$i]['field']='<input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" value="'.$output[$field['dbfield'].'_zip'].'" tabindex="'.($i+4).'" />';
				$cat_content[$i]['class']=(!empty($country_id) && $prefered_input=='z') ? 'visible' : 'invisible';
				break;

		}
	}
	$loop[$c]['cat_content']=$cat_content;
	++$c;
}

//print_r($loop);die;

$tpl->set_file('content','profile_edit.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

$tplvars['title']=sprintf('%1s Member Profile',isset($output['_user']) ? $output['_user'] : '');	// translate
include 'frame.php';
?>
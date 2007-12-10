<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$loop=array();
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];

	$query='SELECT `fk_user_id`,`_user`';
	foreach ($_pfields as $field_id=>$field) {
		if ($field['editable']) {
			switch ($field['field_type']) {

				case FIELD_DATE:
					$query.=",YEAR(`".$field['dbfield']."`) as `".$field['dbfield']."_year`,MONTH(`".$field['dbfield']."`) as `".$field['dbfield']."_month`,DAYOFMONTH(`".$field['dbfield']."`) as `".$field['dbfield']."_day`";
					break;

				case FIELD_LOCATION:
					$query.=",`".$field['dbfield']."_country`,`".$field['dbfield']."_state`,`".$field['dbfield']."_city`,`".$field['dbfield']."_zip`";
					break;

				default:
					$query.=",`".$field['dbfield']."`";

			}
		}
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=$uid";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return2']=rawurldecode($output['return']);
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No user selected';
	redirect2page('admin/cpanel.php',$topass);
}

$l=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$loop[$l]['pcat_name']=$pcat['pcat_name'];
	$cat_content=array();
	$c=0;
	for ($i=0;isset($pcat['fields'][$i]);++$i) {
		$field=$_pfields[$pcat['fields'][$i]];
		if ($field['editable']) {
			$cat_content[$c]['label']=$field['label'];
			$cat_content[$c]['dbfield']=$field['dbfield'];
			switch ($field['field_type']) {

				case FIELD_TEXTFIELD:
					$cat_content[$c]['field']='<input type="text" name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" value="'.(isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '').'" tabindex="'.($i+4).'" />';
					break;

				case FIELD_TEXTAREA:
					$cat_content[$c]['field']='<textarea name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.(isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '').'</textarea>';
					break;

				case FIELD_SELECT:
					$cat_content[$c]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : 0,array(0)).'</select>';
					break;

				case FIELD_CHECKBOX_LARGE:
					$cat_content[$c]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],isset($output[$field['dbfield']]) ? $output[$field['dbfield']] : '',1,true,'tabindex="'.($i+4).'"');
					break;

				case FIELD_DATE:
					$cat_content[$c]['field']='<select name="'.$field['dbfield'].'_month" id="'.$field['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$output[$field['dbfield'].'_month']).'</select>';
					$cat_content[$c]['field'].='<select name="'.$field['dbfield'].'_day" id="'.$field['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">day</option>'.interval2options(1,31,$output[$field['dbfield'].'_day']).'</select>'; // translate
					$cat_content[$c]['field'].='<select name="'.$field['dbfield'].'_year" id="'.$field['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">year</option>'.interval2options($field['accepted_values'][1],$field['accepted_values'][2],$output[$field['dbfield'].'_year'],array(),1,2).'</select>'; // translate
					break;

				case FIELD_LOCATION:
					$country_id=$output[$field['dbfield'].'_country'];
					$state_id=$output[$field['dbfield'].'_state'];
					$cat_content[$c]['label']='Country';	//translate this
					$cat_content[$c]['dbfield']=$field['dbfield'].'_country';
					$cat_content[$c]['field']='<select name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)" class="full_width"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output[$field['dbfield'].'_country']).'</select>';	// translate this
					$prefered_input='s';
					$num_states=0;
					$num_cities=0;
					if (!empty($country_id)) {
						$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`=$country_id";
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						if (mysql_num_rows($res)) {
							list($prefered_input,$num_states)=mysql_fetch_row($res);
						}
					}
					if (!empty($state_id)) {
						$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`=$state_id";
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
						if (mysql_num_rows($res)) {
							$num_cities=mysql_result($res,0,0);
						}
					}
					++$c;
					$cat_content[$c]['label']='State';	//translate this
					$cat_content[$c]['dbfield']=$field['dbfield'].'_state';
					$cat_content[$c]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" class="full_width"><option value="0">Select state</option>';	// translate this
					if (!empty($country_id) && $prefered_input=='s' && !empty($num_states)) {
						$cat_content[$c]['field'].=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$output[$field['dbfield'].'_state'],"`fk_country_id`=$country_id");
					}
					$cat_content[$c]['field'].='</select>';
					$cat_content[$c]['class']=(!empty($country_id) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
					++$c;
					$cat_content[$c]['label']='City';	//translate this
					$cat_content[$c]['dbfield']=$field['dbfield'].'_city';
					$cat_content[$c]['field']='<select name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'" class="full_width"><option value="0">Select city</option>';	// translate this
					if (!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) {
						$cat_content[$c]['field'].=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$output[$field['dbfield'].'_city'],"`fk_state_id`=$state_id");
					}
					$cat_content[$c]['field'].='</select>';
					$cat_content[$c]['class']=(!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible';
					++$c;
					$cat_content[$c]['label']='Zip code';	//translate this
					$cat_content[$c]['dbfield']=$field['dbfield'].'_zip';
					$cat_content[$c]['field']='<input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" value="'.$output[$field['dbfield'].'_zip'].'" tabindex="'.($i+4).'" />';
					$cat_content[$c]['class']=(!empty($country_id) && $prefered_input=='z') ? 'visible' : 'invisible';
					break;

			}
			++$c;
		}
	}
	$loop[$l]['cat_content']=$cat_content;
	++$l;
}

//print_r($loop);die;

$tpl->set_file('content','profile_edit.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

$tplvars['title']=sprintf('%1$s Member Profile',isset($output['_user']) ? $output['_user'] : '');	// translate
$tplvars['page']='profile_edit';
$tplvars['css']='profile_edit.css';
include 'frame.php';

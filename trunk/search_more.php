<?php
/******************************************************************************
newdsb
===============================================================================
File:                       search_more.php
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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(17);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$search_fields=array();
foreach ($_pfields as $field_id=>$field) {
	if (isset($field['searchable'])) {
		$search_fields[]=$field_id;
	}
}
$search=array();
$s=0;
for ($i=0;isset($search_fields[$i]);++$i) {
	$field=$_pfields[$search_fields[$i]];
	if (isset($field['search_type'])) {
		$search[$s]['label']=$field['search_label'];
		$search[$s]['dbfield']=$field['dbfield'];
		switch ($field['search_type']) {

			case _HTML_SELECT_:
				$search[$s]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],isset($field['default_search'][0]) ? $field['default_search'][0] : 0,array(0)).'</select>';
				break;

			case _HTML_CHECKBOX_LARGE_:
				$search[$s]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],$field['default_search'],1,true,'tabindex="'.($i+4).'"');
				break;

			case _HTML_DATE_:
				$search[$s]['field']='<select name="'.$field['dbfield'].'_min" id="'.$field['dbfield'].'_min" tabindex="'.($i+4).'">'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1],$field['default_value'][0]).'</select> - ';
				$search[$s]['field'].='<select name="'.$field['dbfield'].'_max" id="'.$field['dbfield'].'_max" tabindex="'.($i+4).'">'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1],$field['default_value'][1]).'</select>';
				break;

			case _HTML_LOCATION_:
				$search[$s]['label']='Country:';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_country';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$field['default_value'][0]).'</select>';
				$prefered_input='s';
				$num_states=0;
				if (isset($field['default_value'][0])) {
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$field['default_value'][0]."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					list($prefered_input,$num_states)=mysql_fetch_row($res);
				}
				++$s;
				$search[$s]['label']='State:';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_state';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
				$search[$s]['class']=(isset($field['default_value'][0]) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
				++$s;
				$search[$s]['label']='City:';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_city';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
				$search[$s]['class']='invisible';
				++$s;
				$search[$s]['label']='Distance:';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_zip';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_dist" id="'.$field['dbfield'].'_dist" tabindex="'.($i+4).'">'.interval2options(1,10).'</select> miles from zip: <input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" tabindex="'.($i+4).'" size="5" />';
				$search[$s]['class']=(isset($field['default_value'][0]) && $prefered_input=='z') ? 'visible' : 'invisible';
				break;

		}
		++$s;
	}
}

$tpl->set_file('content','search_more.html');
$tpl->set_loop('search',$search);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('search');

$tplvars['title']='Advanced Search';
include 'frame.php';
?>
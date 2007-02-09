<?php
/******************************************************************************
newdsb
===============================================================================
File:                       index.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/vars.inc.php';
require_once 'includes/user_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(0);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');
$tpl->set_file('content','index.html');

$search_fields=$default_search_fields;
$search=array();
$s=0;
for ($i=0;isset($search_fields[$i]);++$i) {
	$field=$_pfields[$search_fields[$i]];
	if (isset($field['search_type'])) {
		$search[$s]['label']=$field['search_label'];
		$search[$s]['dbfield']=$field['dbfield'];
		switch ($field['search_type']) {

			case _HTML_SELECT_:
				$search[$s]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],$field['default_search'][0],array(0)).'</select>';
				break;

			case _HTML_CHECKBOX_LARGE_:
				$search[$s]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],$field['default_search'],2,true,'tabindex="'.($i+4).'"');
				break;

			case _HTML_DATE_:
				$search[$s]['field']='<select name="'.$field['dbfield'].'_min" id="'.$field['dbfield'].'_min" tabindex="'.($i+4).'">'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1],$field['default_value'][0]).'</select> - ';
				$search[$s]['field'].='<select name="'.$field['dbfield'].'_max" id="'.$field['dbfield'].'_max" tabindex="'.($i+4).'">'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1],$field['default_value'][1]).'</select>';
				break;

			case _HTML_LOCATION_:
				$search[$s]['label']='Country';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_country';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" class="big_select" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$field['default_value'][0]).'</select>';
				$prefered_input='s';
				$num_states=0;
				if (isset($field['default_value'][0])) {
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$field['default_value'][0]."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					list($prefered_input,$num_states)=mysql_fetch_row($res);
				}
				++$s;
				$search[$s]['label']='State';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_state';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" class="big_select" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
				$search[$s]['class']=(isset($field['default_value'][0]) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
				++$s;
				$search[$s]['label']='City';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_city';
				$search[$s]['field']='<select name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'" class="big_select"><option value="0">Select city</option></select>';	// translate this
				$search[$s]['class']='invisible';
				++$s;
				$search[$s]['label']='Distance';	// translate this
				$search[$s]['dbfield']=$field['dbfield'].'_zip';
				$search[$s]['field']='<select name="dist" id="dist">'.interval2options(1,10).'</select> <label>miles from zip</label> <input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" tabindex="'.($i+4).'" size="4" />';
				$search[$s]['class']=(isset($field['default_value'][0]) && $prefered_input=='z') ? 'visible' : 'invisible';
				break;

		}
		++$s;
	}
}

$tplvars['title']=$tplvars['sitename'];
$tpl->set_loop('search',$search);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','content',TPL_FINISH | TPL_OPTIONAL | TPL_LOOP | TPL_INCLUDE);
?>
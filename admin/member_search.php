<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/member_search.php
$Revision$
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
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$profile=array();
$profile['astat']=vector2options($accepted_astats);
$profile['pstat']=vector2options($accepted_pstats);
$profile['membership']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`');

$search=array();
$s=0;
for ($i=1;$i<=RELEVANT_FIELDS;++$i) {
	if (isset($_pfields[$i]['searchable'])) {
		$search[$s]['label']=$_pfields[$i]['search_label'];
		$search[$s]['dbfield']=$_pfields[$i]['dbfield'];
		switch ($_pfields[$i]['search_type']) {

			case _HTML_SELECT_:
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'" id="'.$_pfields[$i]['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($_pfields[$i]['accepted_values']).'</select>';
				break;

			case _HTML_CHECKBOX_LARGE_:
				$search[$s]['field']=vector2checkboxes_str($_pfields[$i]['accepted_values'],array(0),$_pfields[$i]['dbfield'],array(0),2,true,'tabindex="'.($i+4).'"');
				break;

			case _HTML_DATE_:
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'_min" id="'.$_pfields[$i]['dbfield'].'_min" tabindex="'.($i+4).'"><option value="0">-</option>'.interval2options(date('Y')-$_pfields[$i]['accepted_values'][2],date('Y')-$_pfields[$i]['accepted_values'][1]).'</select> - ';
				$search[$s]['field'].='<select name="'.$_pfields[$i]['dbfield'].'_max" id="'.$_pfields[$i]['dbfield'].'_max" tabindex="'.($i+4).'"><option value="0">-</option>'.interval2options(date('Y')-$_pfields[$i]['accepted_values'][2],date('Y')-$_pfields[$i]['accepted_values'][1]).'</select>';
				break;

			case _HTML_LOCATION_:
				$search[$s]['label']='Country:';	// translate this
				$search[$s]['dbfield']=$_pfields[$i]['dbfield'].'_country';
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'_country" id="'.$_pfields[$i]['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`').'</select>';
				$prefered_input='s';
				$num_states=0;
				++$s;
				$search[$s]['label']='State:';	// translate this
				$search[$s]['dbfield']=$_pfields[$i]['dbfield'].'_state';
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'_state" id="'.$_pfields[$i]['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
				$search[$s]['class']='invisible';
				++$s;
				$search[$s]['label']='City:';	// translate this
				$search[$s]['dbfield']=$_pfields[$i]['dbfield'].'_city';
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'_city" id="'.$_pfields[$i]['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
				$search[$s]['class']='invisible';
				++$s;
				$search[$s]['label']='Distance:';	// translate this
				$search[$s]['dbfield']=$_pfields[$i]['dbfield'].'_zip';
				$search[$s]['field']='<select name="'.$_pfields[$i]['dbfield'].'_dist" id="'.$_pfields[$i]['dbfield'].'_dist" tabindex="'.($i+4).'">'.interval2options(1,10).'</select> miles from zip: <input type="text" name="'.$_pfields[$i]['dbfield'].'_zip" id="'.$_pfields[$i]['dbfield'].'_zip" tabindex="'.($i+4).'" size="5" />';
				$search[$s]['class']='invisible';
				break;

		}
		++$s;
	} elseif (isset($_pfields[$i])) {
		--$i;
	}
}

$tpl->set_file('content','member_search.html');
$tpl->set_var('profile',$profile);
$tpl->set_loop('search',$search);

$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('search');

$tplvars['title']='Search';
include 'frame.php';
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/member_search.php
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

$output=array();
$output['astat']=vector2options($accepted_astats);
$output['pstat']=vector2options($accepted_pstats);
$output['membership']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`');

$loop=array();
$s=0;
foreach ($_pfields as $k=>$field) {
	$loop[$s]['label']=$field['search_label'];
	$loop[$s]['dbfield']=$field['dbfield'];
	switch ($field['search_type']) {

		case FIELD_SELECT:
			$loop[$s]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values']).'</select>';
			break;

		case FIELD_CHECKBOX_LARGE:
			$loop[$s]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],array(0),2,true,'tabindex="'.($i+4).'"');
			break;

		case FIELD_RANGE:
			if ($field['field_type']==FIELD_DATE) {
				$loop[$s]['field']='<select name="'.$field['dbfield'].'_min" id="'.$field['dbfield'].'_min" tabindex="'.($i+4).'"><option value="0">-</option>'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1]).'</select> - ';
				$loop[$s]['field'].='<select name="'.$field['dbfield'].'_max" id="'.$field['dbfield'].'_max" tabindex="'.($i+4).'"><option value="0">-</option>'.interval2options(date('Y')-$field['accepted_values'][2],date('Y')-$field['accepted_values'][1]).'</select>';
			} elseif ($field['field_type']==FIELD_SELECT) {
				$loop[$s]['field']='<select name="'.$field['dbfield'].'_min" id="'.$field['dbfield'].'_min" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],$field['default_search'][0],array(0)).'</select> - ';
				$loop[$s]['field'].='<select name="'.$field['dbfield'].'_max" id="'.$field['dbfield'].'_max" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],$field['default_search'][1],array(0)).'</select>';
			}
			break;

		case FIELD_LOCATION:
			$loop[$s]['label']='Country';	// translate this
			$loop[$s]['dbfield']=$field['dbfield'].'_country';
			$loop[$s]['field']='<select name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`').'</select>';
			$prefered_input='s';
			$num_states=0;
			++$s;
			$loop[$s]['label']='State';	// translate this
			$loop[$s]['dbfield']=$field['dbfield'].'_state';
			$loop[$s]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
			$loop[$s]['class']='invisible';
			++$s;
			$loop[$s]['label']='City';	// translate this
			$loop[$s]['dbfield']=$field['dbfield'].'_city';
			$loop[$s]['field']='<select name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
			$loop[$s]['class']='invisible';
			++$s;
			$loop[$s]['label']='Distance';	// translate this
			$loop[$s]['dbfield']=$field['dbfield'].'_zip';
			$loop[$s]['field']='<select name="'.$field['dbfield'].'_dist" id="'.$field['dbfield'].'_dist" tabindex="'.($i+4).'">'.interval2options(1,10).'</select> miles from zip: <input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" tabindex="'.($i+4).'" size="5" />';
			$loop[$s]['class']='invisible';
			break;

	}
	++$s;
}

$tpl->set_file('content','member_search.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Search';
$tplvars['css']='member_search.css';
$tplvars['page']='member_search';
include 'frame.php';

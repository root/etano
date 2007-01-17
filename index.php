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
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');
$tpl->set_file('content','index.html');

$search_fields=$default_search_fields;
$search=array();
$s=0;
for ($i=0;isset($search_fields[$i]);++$i) {
	if (isset($_pfields[$search_fields[$i]]['search_type'])) {
		$search[$s]['label']=$_pfields[$search_fields[$i]]['search_label'];
		$search[$s]['dbfield']=$_pfields[$search_fields[$i]]['dbfield'];
		if ($_pfields[$search_fields[$i]]['search_type']==_HTML_SELECT_) {
			$search[$s]['field']='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'" id="'.$_pfields[$search_fields[$i]]['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($_pfields[$search_fields[$i]]['accepted_values'],$_pfields[$search_fields[$i]]['default_search'][0],array(0)).'</select>';
		} elseif ($_pfields[$search_fields[$i]]['search_type']==_HTML_CHECKBOX_LARGE_) {
			$search[$s]['field']=vector2checkboxes_str($_pfields[$search_fields[$i]]['accepted_values'],array(0),$_pfields[$search_fields[$i]]['dbfield'],$_pfields[$search_fields[$i]]['default_search'],1,true,'tabindex="'.($i+4).'"');
		} elseif ($_pfields[$search_fields[$i]]['search_type']==_HTML_DATE_) {
			$search[$s]['field']='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'_min" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_min" tabindex="'.($i+4).'">'.interval2options(date('Y')-$_pfields[$search_fields[$i]]['accepted_values'][2],date('Y')-$_pfields[$search_fields[$i]]['accepted_values'][1],$_pfields[$search_fields[$i]]['default_value'][0]).'</select> - ';
			$search[$s]['field'].='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'_max" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_max" tabindex="'.($i+4).'">'.interval2options(date('Y')-$_pfields[$search_fields[$i]]['accepted_values'][2],date('Y')-$_pfields[$search_fields[$i]]['accepted_values'][1],$_pfields[$search_fields[$i]]['default_value'][1]).'</select>';
		} elseif ($_pfields[$search_fields[$i]]['search_type']==_HTML_LOCATION_) {
			$search[$s]['label']='Country:';	// translate this
			$search[$s]['dbfield']=$_pfields[$search_fields[$i]]['dbfield'].'_country';
			$search[$s]['field']='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'_country" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$_pfields[$search_fields[$i]]['default_value'][0]).'</select>';
			$prefered_input='s';
			$num_states=0;
			if (isset($_pfields[$search_fields[$i]]['default_value'][0])) {
				$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$_pfields[$search_fields[$i]]['default_value'][0]."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				list($prefered_input,$num_states)=mysql_fetch_row($res);
			}
			++$s;
			$search[$s]['label']='State:';	// translate this
			$search[$s]['dbfield']=$_pfields[$search_fields[$i]]['dbfield'].'_state';
			$search[$s]['field']='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'_state" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
			$search[$s]['class']=(isset($_pfields[$search_fields[$i]]['default_value'][0]) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$s;
			$search[$s]['label']='City:';	// translate this
			$search[$s]['dbfield']=$_pfields[$search_fields[$i]]['dbfield'].'_city';
			$search[$s]['field']='<select name="'.$_pfields[$search_fields[$i]]['dbfield'].'_city" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
			$search[$s]['class']='invisible';
			++$s;
			$search[$s]['label']='Distance:';	// translate this
			$search[$s]['dbfield']=$_pfields[$search_fields[$i]]['dbfield'].'_zip';
			$search[$s]['field']='<select name="dist" id="dist">'.interval2options(1,10).'</select> miles from zip: <input type="text" name="'.$_pfields[$search_fields[$i]]['dbfield'].'_zip" id="'.$_pfields[$search_fields[$i]]['dbfield'].'_zip" tabindex="'.($i+4).'" size="5" />';
			$search[$s]['class']=(isset($_pfields[$search_fields[$i]]['default_value'][0]) && $prefered_input=='z') ? 'visible' : 'invisible';
		}
		++$s;
	}
}

$tpl->set_loop('search',$search);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','content',TPL_FINISH | TPL_OPTIONAL | TPL_LOOP | TPL_INCLUDE);
?>
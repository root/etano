<?php
/******************************************************************************
newdsb
===============================================================================
File:                       join.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$page=1;
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	unset($_SESSION['topass']['input']);
	if ($page==1 && $output['agree']==1) {
		$output['agree']='checked="checked"';
	}
} elseif (isset($_GET['p']) && !empty($_GET['p'])) {
	$page=(int)$_GET['p'];
}

// no landing on 2+ join pages.
if ($page>1 && (!isset($_SESSION['user']['reg_id']) || empty($_SESSION['user']['reg_id']))) {
	redirect2page('join.php');
}

$my_fields=array();
foreach ($_pfields as $field_id=>$field) {
	if (isset($field['reg_page']) && $field['reg_page']==$page) {
		$my_fields[]=$field_id;
	}
}

//get the default value for every displayed field
if (empty($output)) {
	for ($i=0;isset($my_fields[$i]);++$i) {
		$field=$_pfields[$my_fields[$i]];
//print_r($field);
		switch ($field['html_type']) {

			case FIELD_SELECT:
				$output[$field['dbfield']]=isset($field['default_value'][0]) ? $field['default_value'][0] : '';
				break;

			case FIELD_CHECKBOX_LARGE:
				$output[$field['dbfield']]=$field['default_value'];
				break;

			case FIELD_DATE:
				$output[$field['dbfield'].'_month']='';
				$output[$field['dbfield'].'_day']='';
				$output[$field['dbfield'].'_year']='';
				break;

			case FIELD_LOCATION:
				if (isset($field['default_value'][0])) {
					$output[$field['dbfield'].'_country']=$field['default_value'][0];
				}
				break;

			default:
				$output[$field['dbfield']]='';

		}	// switch()
	}	// for()
}

$loop=array();
$j=0;
for ($i=0;isset($my_fields[$i]);++$i) {
	$field=$_pfields[$my_fields[$i]];
	$loop[$j]['label']=$field['label'];
	$loop[$j]['dbfield']=$field['dbfield'];
	$loop[$j]['required']=isset($field['required']) ? true : false;
	if ($loop[$j]['required']) {
		$loop[$j]['class']='required';
	}
	$loop[$j]['help_text']=$field['help_text'];
	if (isset($output['error_'.$field['dbfield']])) {
		$loop[$j]['class_error']=$output['error_'.$field['dbfield']];
		unset($output['error_'.$field['dbfield']]);
	}
	switch ($field['html_type']) {

		case FIELD_TEXTFIELD:
			$loop[$j]['field']='<input type="text" name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" value="'.$output[$field['dbfield']].'" tabindex="'.($i+4).'" />';
			break;

		case FIELD_TEXTAREA:
			$loop[$j]['field']='<textarea name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.$output[$field['dbfield']].'</textarea>';
			break;

		case FIELD_SELECT:
			$loop[$j]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],$output[$field['dbfield']],array(0)).'</select>';
			break;

		case FIELD_CHECKBOX_LARGE:
			$loop[$j]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],$output[$field['dbfield']],2,true,'tabindex="'.($i+4).'"');
			break;

		case FIELD_DATE:
			$loop[$j]['field']='<select name="'.$field['dbfield'].'_month" id="'.$field['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$output[$field['dbfield'].'_month']).'</select>';
			$loop[$j]['field'].='<select name="'.$field['dbfield'].'_day" id="'.$field['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">day</option>'.interval2options(1,31,$output[$field['dbfield'].'_day']).'</select>';	// translate
			$loop[$j]['field'].='<select name="'.$field['dbfield'].'_year" id="'.$field['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">year</option>'.interval2options($field['accepted_values'][1],$field['accepted_values'][2],$output[$field['dbfield'].'_year'],array(),1,2).'</select>'; // translate
			break;

		case FIELD_LOCATION:
			$loop[$j]['label']='Country';	// translate this
			$loop[$j]['dbfield']=$field['dbfield'].'_country';
			$loop[$j]['field']='<select class="text" name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output[$field['dbfield'].'_country']).'</select>';
			$prefered_input='s';
			$num_states=0;
			if (!empty($output[$field['dbfield'].'_country'])) {
				$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$output[$field['dbfield'].'_country']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				list($prefered_input,$num_states)=mysql_fetch_row($res);
			}
			++$j;
			$loop[$j]['label']='State';	// translate this
			$loop[$j]['dbfield']=$field['dbfield'].'_state';
			$loop[$j]['field']='<select class="text" name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
			$loop[$j]['class']=(!empty($output[$field['dbfield'].'_country']) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$j;
			$loop[$j]['label']='City';	// translate this
			$loop[$j]['dbfield']=$field['dbfield'].'_city';
			$loop[$j]['field']='<select class="text" name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
			$loop[$j]['class']='invisible';
			++$j;
			$loop[$j]['label']='Zip';	// translate this
			$loop[$j]['dbfield']=$field['dbfield'].'_zip';
			$loop[$j]['field']='<input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" tabindex="'.($i+4).'" />';
			$loop[$j]['class']=(!empty($output[$field['dbfield'].'_country']) && $prefered_input=='z') ? 'visible' : 'invisible';
			break;

	}
	++$j;
	unset($output[$field['dbfield']]);
}

if ($page==1) {
	$output['page1']=true;
	if (get_site_option('use_captcha','core')) {
		require_once 'includes/classes/sco_captcha.class.php';
		$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
		$_SESSION['captcha_word']=$c->gen_rnd_string(4);
		$output['rand']=make_seed();
		$output['use_captcha']=true;
	}
}
$output['page']=$page;

$tpl->set_file('content','join.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Registration';
$tplvars['page_title']='Registration';
$tplvars['page']='join';
$tplvars['css']='join.css';
if (is_file('join_left.php')) {
	include 'join_left.php';
}
include 'frame.php';
?>
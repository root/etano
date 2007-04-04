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

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/vars.inc.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/classes/sco_captcha.class.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$page=1;
$my_values=array();
if (isset($_SESSION['topass']['input'])) {
	$my_values=$_SESSION['topass']['input'];
	$page=$my_values['page'];
	unset($_SESSION['topass']['input']);
	if ($page==1) {
		$my_values['agree']='checked';
	}
} elseif (isset($_GET['p']) && !empty($_GET['p'])) {
	$page=(int)$_GET['p'];
}

$my_fields=array();
foreach ($_pfields as $field_id=>$field) {
	if (isset($field['reg_page']) && $field['reg_page']==$page) {
		$my_fields[]=$field_id;
	}
}
if (empty($my_values)) {
	for ($i=0;isset($my_fields[$i]);++$i) {
		if ($_pfields[$my_fields[$i]]['html_type']==HTML_SELECT) {
			$my_values[$_pfields[$my_fields[$i]]['dbfield']]=isset($_pfields[$my_fields[$i]]['default_value'][0]) ? $_pfields[$my_fields[$i]]['default_value'][0] : '';
		} elseif ($_pfields[$my_fields[$i]]['html_type']==HTML_CHECKBOX_LARGE) {
			$my_values[$_pfields[$my_fields[$i]]['dbfield']]=$_pfields[$my_fields[$i]]['default_value'];
		} elseif ($_pfields[$my_fields[$i]]['html_type']==HTML_DATE) {
			$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_month']='';
			$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_day']='';
			$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_year']='';
		} elseif ($_pfields[$my_fields[$i]]['html_type']==HTML_LOCATION && isset($_pfields[$my_fields[$i]]['default_value'][0])) {
			$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country']=$_pfields[$my_fields[$i]]['default_value'][0];
		} else {
			$my_values[$_pfields[$my_fields[$i]]['dbfield']]='';
		}
	}
}


$my_rows=array();
$j=0;
for ($i=0;isset($my_fields[$i]);++$i) {
	$my_rows[$j]['label']=$_pfields[$my_fields[$i]]['label'];
	$my_rows[$j]['dbfield']=$_pfields[$my_fields[$i]]['dbfield'];
	$my_rows[$j]['required']=isset($_pfields[$my_fields[$i]]['required']) ? true : false;
	$my_rows[$j]['help_text']=$_pfields[$my_fields[$i]]['help_text'];
	if (isset($my_values['error_'.$_pfields[$my_fields[$i]]['dbfield']])) {
		$my_rows[$j]['class_error']=$my_values['error_'.$_pfields[$my_fields[$i]]['dbfield']];
		unset($my_values['error_'.$_pfields[$my_fields[$i]]['dbfield']]);
	}
	switch ($_pfields[$my_fields[$i]]['html_type']) {

		case HTML_TEXTFIELD:
			$my_rows[$j]['field']='<input type="text" name="'.$_pfields[$my_fields[$i]]['dbfield'].'" id="'.$_pfields[$my_fields[$i]]['dbfield'].'" value="'.$my_values[$_pfields[$my_fields[$i]]['dbfield']].'" tabindex="'.($i+4).'" />';
			break;

		case HTML_TEXTAREA:
			$my_rows[$j]['field']='<textarea name="'.$_pfields[$my_fields[$i]]['dbfield'].'" id="'.$_pfields[$my_fields[$i]]['dbfield'].'" tabindex="'.($i+4).'">'.$my_values[$_pfields[$my_fields[$i]]['dbfield']].'</textarea>';
			break;

		case HTML_SELECT:
			$my_rows[$j]['field']='<select name="'.$_pfields[$my_fields[$i]]['dbfield'].'" id="'.$_pfields[$my_fields[$i]]['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($_pfields[$my_fields[$i]]['accepted_values'],$my_values[$_pfields[$my_fields[$i]]['dbfield']],array(0)).'</select>';
			break;

		case HTML_CHECKBOX_LARGE:
			$my_rows[$j]['field']=vector2checkboxes_str($_pfields[$my_fields[$i]]['accepted_values'],array(0),$_pfields[$my_fields[$i]]['dbfield'],$my_values[$_pfields[$my_fields[$i]]['dbfield']],2,true,'tabindex="'.($i+4).'"');
			break;

		case HTML_DATE:
			$my_rows[$j]['field']='<select name="'.$_pfields[$my_fields[$i]]['dbfield'].'_month" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_month']).'</select>';
			$my_rows[$j]['field'].='<select name="'.$_pfields[$my_fields[$i]]['dbfield'].'_day" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">day</option>'.interval2options(1,31,$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_day']).'</select>';	// translate
			$my_rows[$j]['field'].='<select name="'.$_pfields[$my_fields[$i]]['dbfield'].'_year" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">year</option>'.interval2options($_pfields[$my_fields[$i]]['accepted_values'][1],$_pfields[$my_fields[$i]]['accepted_values'][2],$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_year'],array(),1,2).'</select>'; // translate
			break;

		case HTML_LOCATION:
			$my_rows[$j]['label']='Country';	// translate this
			$my_rows[$j]['dbfield']=$_pfields[$my_fields[$i]]['dbfield'].'_country';
			$my_rows[$j]['field']='<select class="text" name="'.$_pfields[$my_fields[$i]]['dbfield'].'_country" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select country</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country']).'</select>';
			$prefered_input='s';
			$num_states=0;
			if (!empty($my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country'])) {
				$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				list($prefered_input,$num_states)=mysql_fetch_row($res);
			}
			++$j;
			$my_rows[$j]['label']='State';	// translate this
			$my_rows[$j]['dbfield']=$_pfields[$my_fields[$i]]['dbfield'].'_state';
			$my_rows[$j]['field']='<select class="text" name="'.$_pfields[$my_fields[$i]]['dbfield'].'_state" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">Select state</option></select>';	// translate this
			$my_rows[$j]['class']=(!empty($my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country']) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$j;
			$my_rows[$j]['label']='City';	// translate this
			$my_rows[$j]['dbfield']=$_pfields[$my_fields[$i]]['dbfield'].'_city';
			$my_rows[$j]['field']='<select class="text" name="'.$_pfields[$my_fields[$i]]['dbfield'].'_city" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">Select city</option></select>';	// translate this
			$my_rows[$j]['class']='invisible';
			++$j;
			$my_rows[$j]['label']='Zip';	// translate this
			$my_rows[$j]['dbfield']=$_pfields[$my_fields[$i]]['dbfield'].'_zip';
			$my_rows[$j]['field']='<input type="text" name="'.$_pfields[$my_fields[$i]]['dbfield'].'_zip" id="'.$_pfields[$my_fields[$i]]['dbfield'].'_zip" tabindex="'.($i+4).'" />';
			$my_rows[$j]['class']=(!empty($my_values[$_pfields[$my_fields[$i]]['dbfield'].'_country']) && $prefered_input=='z') ? 'visible' : 'invisible';
			break;

	}
	++$j;
	unset($my_values[$_pfields[$my_fields[$i]]['dbfield']]);
}

$tpl->set_file('content','join.html');
$tpl->set_loop('my_rows',$my_rows);
$tpl->set_var('my_values',$my_values);
$tpl->set_var('page',$page);
if ($page==1) {
	$tpl->set_var('page1',true);
	if (get_site_option('use_captcha','core')) {
		$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
		$captcha_word=$c->gen_rnd_string(4);
		$_SESSION['captcha_word']=$captcha_word;
		$tpl->set_var('rand',make_seed());
	}
}
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('my_rows');

$tplvars['title']='Registration';
$tplvars['page_title']='Registration';
$tplvars['page']='join';
$tplvars['css']='join.css';
if (is_file('join_left.php')) {
	include 'join_left.php';
}
include 'frame.php';
?>
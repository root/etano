<?php
/******************************************************************************
Etano
===============================================================================
File:                       join.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/join.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$page=1;
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$page=$output['page'];
	if ($page==1 && $output['agree']==1) {
		$output['agree']='checked="checked"';
	}
	unset($_SESSION['topass']['input'],$output['page']);
} elseif (!empty($_GET['p'])) {
	$page=(int)$_GET['p'];
}

// no landing on 2+ join pages.
if ($page>1 && (!isset($_SESSION[_LICENSE_KEY_]['user']['reg_id']) || empty($_SESSION[_LICENSE_KEY_]['user']['reg_id']))) {
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
		switch ($field['field_type']) {

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
				$output[$field['dbfield'].'_country']=0;
				$output[$field['dbfield'].'_state']=0;
				$output[$field['dbfield'].'_city']=0;
				$output[$field['dbfield'].'_zip']='';
				if (isset($field['default_value'][0])) {
					$output[$field['dbfield'].'_country']=$field['default_value'][0];
				} else {
					$output[$field['dbfield'].'_country']=0;
				}
				break;

			default:
				$output[$field['dbfield']]='';

		}	// switch()
	}	// for()
}

$config=get_site_option(array('ta_len'),'core');
$loop=array();
$js_loop=array();
$j=0;
$js=0;
for ($i=0;isset($my_fields[$i]);++$i) {
	$field=$_pfields[$my_fields[$i]];
	$loop[$j]['label']=$field['label'];
	$loop[$j]['dbfield']=$field['dbfield'];
	$loop[$j]['required']=isset($field['required']) ? true : false;
	if ($loop[$j]['required']) {
		$loop[$j]['class']='required';
		$js_loop[$js]['dbfield']=$field['dbfield'];
		$js_loop[$js]['field_type']=$field['field_type'];
		$js_loop[$js]['i']=$js;
		++$js;
	}
	$loop[$j]['help_text']=$field['help_text'];
	if (isset($output['error_'.$field['dbfield']])) {
		$loop[$j]['class_error']=$output['error_'.$field['dbfield']];
		unset($output['error_'.$field['dbfield']]);
	}
	switch ($field['field_type']) {

		case FIELD_TEXTFIELD:
			$loop[$j]['field']='<input type="text" name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" value="'.$output[$field['dbfield']].'" tabindex="'.($i+4).'" />';
			break;

		case FIELD_TEXTAREA:
			$loop[$j]['field']='<textarea name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'" cols="" rows="">'.$output[$field['dbfield']].'</textarea>';
			if (!empty($config['ta_len'])) {
				$loop[$j]['field'].='<p class="comment char_counter">'.$GLOBALS['_lang'][125].' <span id="'.$field['dbfield'].'_chars">'.($config['ta_len']-strlen($output[$field['dbfield']])).'</span></p>';
			}
			break;

		case FIELD_SELECT:
			$loop[$j]['field']='<select name="'.$field['dbfield'].'" id="'.$field['dbfield'].'" tabindex="'.($i+4).'">'.vector2options($field['accepted_values'],$output[$field['dbfield']],array(0)).'</select>';
			break;

		case FIELD_CHECKBOX_LARGE:
			$loop[$j]['field']=vector2checkboxes_str($field['accepted_values'],array(0),$field['dbfield'],$output[$field['dbfield']],2,true,'tabindex="'.($i+4).'"');
			break;

		case FIELD_DATE:
			$loop[$j]['field']='<select name="'.$field['dbfield'].'_month" id="'.$field['dbfield'].'_month" tabindex="'.($i+4).'">'.vector2options($accepted_months,$output[$field['dbfield'].'_month']).'</select>';
			$loop[$j]['field'].='<select name="'.$field['dbfield'].'_day" id="'.$field['dbfield'].'_day" tabindex="'.($i+4).'"><option value="">'.$GLOBALS['_lang'][131].'</option>'.interval2options(1,31,$output[$field['dbfield'].'_day']).'</select>';
			$loop[$j]['field'].='<select name="'.$field['dbfield'].'_year" id="'.$field['dbfield'].'_year" tabindex="'.($i+4).'"><option value="">'.$GLOBALS['_lang'][132].'</option>'.interval2options($field['accepted_values'][1],$field['accepted_values'][2],$output[$field['dbfield'].'_year'],array(),1,2).'</select>';
			break;

		case FIELD_LOCATION:
			$country_id=$output[$field['dbfield'].'_country'];
			$state_id=$output[$field['dbfield'].'_state'];
			$loop[$j]['dbfield']=$field['dbfield'].'_country';
			$loop[$j]['field']='<select class="text" name="'.$field['dbfield'].'_country" id="'.$field['dbfield'].'_country" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">'.$GLOBALS['_lang'][195].'</option>'.dbtable2options("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`','`country`',$output[$field['dbfield'].'_country']).'</select>';
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
			++$j;
			$loop[$j]['label']=$GLOBALS['_lang'][127];
			$loop[$j]['dbfield']=$field['dbfield'].'_state';
			$loop[$j]['field']='<select name="'.$field['dbfield'].'_state" id="'.$field['dbfield'].'_state" tabindex="'.($i+4).'" onchange="req_update_location(this.id,this.value)"><option value="0">'.$GLOBALS['_lang'][133].'</option>';
			if (!empty($country_id) && $prefered_input=='s' && !empty($num_states)) {
				$loop[$j]['field'].=dbtable2options("`{$dbtable_prefix}loc_states`",'`state_id`','`state`','`state`',$state_id,"`fk_country_id`=$country_id");
			}
			$loop[$j]['field'].='</select>';
			$loop[$j]['class']=(!empty($country_id) && $prefered_input=='s' && !empty($num_states)) ? 'visible' : 'invisible';
			++$j;
			$loop[$j]['label']=$GLOBALS['_lang'][128];
			$loop[$j]['dbfield']=$field['dbfield'].'_city';
			$loop[$j]['field']='<select class="text" name="'.$field['dbfield'].'_city" id="'.$field['dbfield'].'_city" tabindex="'.($i+4).'"><option value="0">'.$GLOBALS['_lang'][134].'</option>';
			if (!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) {
				$loop[$j]['field'].=dbtable2options("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`','`city`',$output[$field['dbfield'].'_city'],"`fk_state_id`=$state_id");
			}
			$loop[$j]['field'].='</select>';
			$loop[$j]['class']=(!empty($state_id) && $prefered_input=='s' && !empty($num_cities)) ? 'visible' : 'invisible';
			++$j;
			$loop[$j]['label']=$GLOBALS['_lang'][129];
			$loop[$j]['dbfield']=$field['dbfield'].'_zip';
			$loop[$j]['field']='<input type="text" name="'.$field['dbfield'].'_zip" id="'.$field['dbfield'].'_zip" value="'.$output[$field['dbfield'].'_zip'].'" tabindex="'.($i+4).'" />';
			$loop[$j]['class']=(!empty($country_id) && $prefered_input=='z') ? 'visible' : 'invisible';
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
$output['ta_len']=$config['ta_len'];

$output['lang_64']=sanitize_and_format($GLOBALS['_lang'][64],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_272']=sanitize_and_format($GLOBALS['_lang'][272],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_63']=sanitize_and_format($GLOBALS['_lang'][63],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_37']=sanitize_and_format($GLOBALS['_lang'][37],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_66']=sanitize_and_format($GLOBALS['_lang'][66],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_259']=sanitize_and_format($GLOBALS['_lang'][259],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_68']=sanitize_and_format($GLOBALS['_lang'][68],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_69']=sanitize_and_format($GLOBALS['_lang'][69],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','join.html');
$tpl->set_loop('loop',$loop);
$tpl->set_loop('js_loop',$js_loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
$tpl->drop_loop('js_loop');
unset($loop,$js_loop);

$tplvars['title']=$GLOBALS['_lang'][130];
$tplvars['page_title']=$GLOBALS['_lang'][130];
$tplvars['page']='join';
$tplvars['css']='join.css';
if (is_file('join_left.php')) {
	include 'join_left.php';
}
$no_timeout=true;
include 'frame.php';

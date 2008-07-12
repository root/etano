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
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/join.inc.php';

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$output=array();
$page=1;
$my_fields=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output=sanitize_and_format($output,TYPE_STRING,FORMAT_STRIP_MQ);
	$page=$output['page'];
	$agree=false;
	if ($page==1 && $output['agree']==1) {
		$agree=true;
	}
	unset($_SESSION['topass']['input']);
	if ($agree) {
		$output['agree']='checked="checked"';
	}
} elseif (!empty($_GET['p'])) {
	$page=(int)$_GET['p'];
}

foreach ($_pfields as $field_id=>&$field) {
	if (isset($field->config['reg_page']) && $field->config['reg_page']==$page) {
		$my_fields[]=$field_id;
		$_pfields[$field_id]->set_value($output,false);
	}
}

// no landing on 2+ join pages.
if ($page>1 && empty($_SESSION[_LICENSE_KEY_]['user']['reg_id'])) {
	redirect2page('join.php');
}

$loop=array();
$j=0;
for ($i=0;isset($my_fields[$i]);++$i) {
	$field=&$_pfields[$my_fields[$i]];
	$loop[$i]['label']=$field->config['label'];
	$loop[$i]['dbfield']=$field->config['dbfield'];
	$loop[$i]['required']=isset($field->config['required']) ? true : false;
	$loop[$i]['help_text']=$field->config['help_text'];
	$loop[$i]['js']=$field->edit_js();
	$loop[$i]['field']=$field->edit($i+6);
/*??????????????????????????????????????????????????????????????????????????????????????????????????????????????
	if (isset($output['error_'.$field->config['dbfield']])) {
		$loop[$j]['class_error']=$output['error_'.$field->config['dbfield']];
		unset($output['error_'.$field->config['dbfield']]);
	}
*/
}

if ($page==1) {
	$output['page1']=true;
	if (get_site_option('use_captcha','core')) {
		require _BASEPATH_.'/includes/classes/sco_captcha.class.php';
		$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
		$_SESSION['captcha_word']=$c->gen_rnd_string(4);
		$output['rand']=make_seed();
		$output['use_captcha']=true;
	}
}
$output['page']=$page;

$output['lang_37']=sanitize_and_format($GLOBALS['_lang'][37],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_63']=sanitize_and_format($GLOBALS['_lang'][63],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_64']=sanitize_and_format($GLOBALS['_lang'][64],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_65']=sanitize_and_format($GLOBALS['_lang'][65],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_66']=sanitize_and_format($GLOBALS['_lang'][66],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_68']=sanitize_and_format($GLOBALS['_lang'][68],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_69']=sanitize_and_format($GLOBALS['_lang'][69],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_259']=sanitize_and_format($GLOBALS['_lang'][259],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_272']=sanitize_and_format($GLOBALS['_lang'][272],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','join.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][130];
$tplvars['page_title']=$GLOBALS['_lang'][130];
$tplvars['page']='join';
$tplvars['css']='join.css';
if (is_file('join_left.php')) {
	include 'join_left.php';
}
$no_timeout=true;
include 'frame.php';

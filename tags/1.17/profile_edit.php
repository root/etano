<?php
/******************************************************************************
Etano
===============================================================================
File:                       profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$cid=1;
$used_fields=array();
if (isset($_SESSION['topass']['input'])) {
	$user_details=&$_SESSION['topass']['input'];
	$user_details=sanitize_and_format($user_details,TYPE_STRING,FORMAT_STRIP_MQ);
	$cid=$user_details['pcat_id'];
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		if ($_pfields[$field_id]->config['editable']) {
			$used_fields[]=$field_id;
			$_pfields[$field_id]->set_value($user_details,false);
		}
	}
	unset($_SESSION['topass']['input']);
} elseif (!empty($_GET['cid'])) {
	$cid=(int)$_GET['cid'];

	if (!isset($_pcats[$cid])) {
		$cid=1;
	}
	$query='SELECT 1';
	foreach ($_pcats[$cid]['fields'] as $field_id) {
		if ($_pfields[$field_id]->config['editable']) {
			$query.=','.$_pfields[$field_id]->query_select();
			$used_fields[]=$field_id;
		}
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user_details=mysql_fetch_assoc($res);
		for ($i=0;isset($used_fields[$i]);++$i) {
			$_pfields[$used_fields[$i]]->set_value($user_details,false);
		}
		unset($user_details);
	}
}

$tplvars['pcat_name']=$_pcats[$cid]['pcat_name'];
$tplvars['pcat_id']=$cid;
$loop=array();
for ($i=0;isset($used_fields[$i]);++$i) {
	$field=&$_pfields[$used_fields[$i]];
	$loop[$i]['label']=$field->config['label'];
	$loop[$i]['dbfield']=$field->config['dbfield'];
	$loop[$i]['required']=isset($field->config['required']) ? true : false;
	$loop[$i]['help_text']=$field->config['help_text'];
	$loop[$i]['js']=$field->edit_js();
	if (isset($user_details['error_'.$field->config['dbfield']])) {
		$loop[$i]['class_error']=$user_details['error_'.$field['dbfield']];
	}
	$loop[$i]['field']=$field->edit($i+4);
}
$output['lang_69']=sanitize_and_format($GLOBALS['_lang'][69],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
unset($user_details);

$tpl->set_file('content','profile_edit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][141].' - '.$_pcats[$cid]['pcat_name'];
$tplvars['page_title']='<a href="'.$tplvars['relative_url'].'my_profile.php">'.$GLOBALS['_lang'][141].'</a> - '.$_pcats[$cid]['pcat_name'];
$tplvars['page']='profile_edit';
$tplvars['css']='profile_edit.css';
if (is_file('profile_edit_left.php')) {
	include 'profile_edit_left.php';
}
include 'frame.php';

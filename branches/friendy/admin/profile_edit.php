<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$loop=array();
$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
	$query="SELECT `_user` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$output['fk_user_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['_user']=mysql_result($res,0,0);
	}
} elseif (!empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];

	$query='SELECT `fk_user_id`,`_user`';
	foreach ($_pfields as $field_id=>$field) {
		$query.=','.$field->query_select();
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=$uid";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user_details=mysql_fetch_assoc($res);
		foreach ($_pfields as $field_id=>$field) {
			$_pfields[$field_id]->set_value($user_details,false);
		}
		$output['fk_user_id']=$user_details['fk_user_id'];
		$output['_user']=$user_details['_user'];
		unset($user_details);
	}
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No user selected';
	redirect2page('admin/cpanel.php',$topass);
}

$js_loop=array();
$l=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$loop[$l]['pcat_name']=$pcat['pcat_name'];
	$cat_content=array();
	for ($i=0;isset($pcat['fields'][$i]);++$i) {
		$temp=array();
		$field=&$_pfields[$pcat['fields'][$i]];
		if (isset($output['error_'.$field->config['dbfield']])) {
			$temp['class_error']=$output['error_'.$field->config['dbfield']];
			unset($output['error_'.$field->config['dbfield']]);
		}
		$temp=array_merge($temp,array('field'=>$field->edit(),'label'=>$field->config['label'],'dbfield'=>$field->config['dbfield']));
		$cat_content[]=$temp;
		$js_loop[]=array('js'=>$field->edit_js());
	}
	$loop[$l]['cat_content']=$cat_content;
	++$l;
}

//print_r($loop);die;

$tpl->set_file('content','profile_edit.html');
$tpl->set_loop('loop',$loop);
$tpl->set_loop('js_loop',$js_loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

$tplvars['title']=sprintf('%1$s Member Profile',isset($output['_user']) ? $output['_user'] : '');
$tplvars['page']='profile_edit';
$tplvars['css']='profile_edit.css';
include 'frame.php';

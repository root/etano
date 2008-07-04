<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_profile.php
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

$query="SELECT `fk_user_id`,`_photo`,`_user`,`alt_url`,`rad_longitude`,`rad_latitude`,`score`,`status`,`reject_reason`";
foreach ($_pfields as $field_id=>&$field) {
	if ($field->config['visible']) {
		$query.=','.$field->query_select();
	}
}

$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
	// set all the fields to their real (readable) values
	foreach ($_pfields as $field_id=>&$field) {
		if ($field->config['visible']) {
			$field->set_value($output,false);
		}
	}
	if (empty($output['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$output['_photo'])) {
		unset($output['_photo']);
	}
}
/*
			} elseif ($field['field_type']==FIELD_INT || $field['field_type']==FIELD_FLOAT) {
	//			$output[$field['dbfield']]=$output[$field['dbfield']];
*/

$user_photos=array();
$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `del`=0 LIMIT 5";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	while (count($user_photos)<3 && $rsrow=mysql_fetch_assoc($res)) {
		if (is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$user_photos[]=$rsrow;
		}
	}
	$user_photos[0]['class']='first';
	$output['num_photos']=mysql_num_rows($res);
}

$categs=array();
$i=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	$fields=array();
	for ($j=0;isset($pcat['fields'][$j]);++$j) {
		if ($_pfields[$pcat['fields'][$j]]->config['visible']) {
			$fields[]=array('field'=>$_pfields[$pcat['fields'][$j]]->display(),'label'=>$_pfields[$pcat['fields'][$j]]->config['label'],'dbfield'=>$_pfields[$pcat['fields'][$j]]->config['dbfield']);
		}
	}
	$categs[$i]['pcat_name']=$pcat['pcat_name'];
	$categs[$i]['pcat_id']=$pcat_id;
	$categs[$i]['fields']=$fields;
	++$i;
}

$output['pic_width']=get_site_option('pic_width','core_photo');

// comments
$loop_comments=create_comments_loop('user',$_SESSION[_LICENSE_KEY_]['user']['user_id'],$output);

$output['lang_256']=sanitize_and_format($GLOBALS['_lang'][256],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='my_profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_profile.html');
$tpl->set_loop('user_photos',$user_photos);
$tpl->set_loop('categs',$categs);
$tpl->set_loop('loop_comments',$loop_comments);
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
unset($categs);

$tplvars['title']=$GLOBALS['_lang'][141];
$tplvars['page_title']=$GLOBALS['_lang'][141];
$tplvars['page']='my_profile';
$tplvars['css']='my_profile.css';
if (is_file('my_profile_left.php')) {
	include 'my_profile_left.php';
}
unset($page_last_modified_time);	// we want everything fresh on this page.
include 'frame.php';

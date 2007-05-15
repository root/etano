<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(-1);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$config=get_site_option(array('bbcode_profile'),'core');

$query="SELECT * FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$output=mysql_fetch_assoc($res);
// set all the fields to their real (readable) values
foreach ($_pfields as $field_id=>$field) {
	if ($field['visible']) {
		if ($field['field_type']==FIELD_TEXTFIELD) {
			$output[$field['dbfield']]=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		} elseif ($field['field_type']==FIELD_TEXTAREA) {
			$output[$field['dbfield']]=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			if ($config['bbcode_profile']) {
				$output[$field['dbfield']]=bbcode2html($output[$field['dbfield']]);
			}
		} elseif ($field['field_type']==FIELD_SELECT) {
			// if we sanitize here " will be rendered as &quot; which is not what we want
//			$output[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$output[$field['dbfield']]],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$output[$field['dbfield']]=$field['accepted_values'][$output[$field['dbfield']]];
		} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
			$output[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$output[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		} elseif ($field['field_type']==FIELD_INT || $field['field_type']==FIELD_FLOAT) {
//			$output[$field['dbfield']]=$output[$field['dbfield']];
		} elseif ($field['field_type']==FIELD_DATE) {
//			$output[$field['dbfield']]=$output[$field['dbfield']];
		} elseif ($field['field_type']==FIELD_LOCATION) {
			$output[$field['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$output[$field['dbfield'].'_country'],'-');
			if (!empty($output[$field['dbfield'].'_state'])) {
				$output[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$output[$field['dbfield'].'_state'],'-');
			}
			if (!empty($output[$field['dbfield'].'_city'])) {
				$output[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$output[$field['dbfield'].'_city'],'-');
			}
		}
	} else {
		unset($output[$field['dbfield']]);
	}
}

if (empty($output['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$output['_photo'])) {
	unset($output['_photo']);
}

$user_photos=array();
$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `del`=0";
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
		$fields[$j]['label']=$_pfields[$pcat['fields'][$j]]['label'];
		$fields[$j]['field']=$output[$_pfields[$pcat['fields'][$j]]['dbfield']];
	}
	$categs[$i]['pcat_name']=$pcat['pcat_name'];
	$categs[$i]['pcat_id']=$pcat_id;
	$categs[$i]['fields']=$fields;
	++$i;
}

$output['pic_width']=get_site_option('pic_width','core_photo');

// comments
$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);
$loop_comments=array();
if (get_user_settings($_SESSION['user']['user_id'],'def_user_prefs','profile_comments')) {
	// may I see any comment?
	$output['show_comments']=true;
	$output['uid']=$_SESSION['user']['user_id'];
	$config=get_site_option(array('bbcode_comments','smilies_comm'),'core');
	$query="SELECT a.`comment_id`,a.`comment`,a.`fk_user_id`,a.`_user` as `user`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`_photo` as `photo` FROM `{$dbtable_prefix}profile_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_user_id_profile`='".$_SESSION['user']['user_id']."' AND a.`status`=".STAT_APPROVED." ORDER BY a.`comment_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		// if someone has asked to edit his/her comment
		if ($edit_comment==$rsrow['comment_id']) {
			$output['comment_id']=$rsrow['comment_id'];
			$output['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		}
		$rsrow['date_posted']=strftime($_user_settings['datetime_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
		$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (!empty($config['bbcode_comments'])) {
			$rsrow['comment']=bbcode2html($rsrow['comment']);
		}
		if (!empty($config['smilies_comm'])) {
			$rsrow['comment']=text2smilies($rsrow['comment']);
		}
		// allow showing the edit links to rightfull owners
		if ($rsrow['fk_user_id']==$_SESSION['user']['user_id']) {
			$rsrow['editme']=true;
		}

		if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
			unset($rsrow['fk_user_id']);
		}
		if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$rsrow['photo']='no_photo.gif';
		}
		$loop_comments[]=$rsrow;
	}

	if (!empty($loop_comments)) {
		$output['num_comments']=count($loop_comments);
	}

	// may I post comments please?
	if (allow_at_level(9,$_SESSION['user']['membership'])) {
		$output['allow_comments']=true;
		// would you let me use bbcode?
		if (!empty($config['bbcode_comments'])) {
			$output['bbcode_comments']=true;
		}
		// if we came back after an error get what was previously posted
		if (isset($_SESSION['topass']['input'])) {
			$output=array_merge($output,$_SESSION['topass']['input']);
			unset($_SESSION['topass']['input']);
		}
	}
}

$output['return2me']='my_profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	if (!empty($edit_comment)) {
		$_SERVER['QUERY_STRING']=str_replace('&edit_comment='.$edit_comment,'',$_SERVER['QUERY_STRING']);
	}
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_profile.html');
$tpl->set_loop('user_photos',$user_photos);
$tpl->set_loop('categs',$categs);
$tpl->set_loop('loop_comments',$loop_comments);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
unset($categs);

$tplvars['title']='My Profile';
$tplvars['page_title']='My Profile';
$tplvars['page']='my_profile';
$tplvars['css']='my_profile.css';
if (is_file('my_profile_left.php')) {
	include 'my_profile_left.php';
}
include 'frame.php';
?>
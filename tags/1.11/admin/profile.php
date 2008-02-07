<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

// cleanup after an 'impersonate user' action
if (isset($_GET['clean_user_session'])) {
	$_SESSION[_LICENSE_KEY_]['user']=array();
	unset($_SESSION[_LICENSE_KEY_]['user']);
}
$tpl=new phemplate('skin/','remove_nonjs');

$output=array('_user'=>'');	// needed for the title
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$uid=0;
if (!empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];
	if (!empty($output['search_md5']) && isset($_GET['go']) && ($_GET['go']==1 || $_GET['go']==-1)) {
		$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$user_ids=mysql_result($res,0,0);
			$user_ids=explode(',',$user_ids);
			$key=array_search($uid,$user_ids)+$_GET['go'];
			if (isset($user_ids[$key])) {
				$uid=(int)$user_ids[$key];
			}
		}
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No user selected';
	redirect2page('admin/cpanel.php',$topass);
}

$config=get_site_option(array('bbcode_profile','datetime_format','time_offset'),'core');
$config=array_merge($config,get_site_option(array('datetime_format','time_offset'),'def_user_prefs'));

$categs=array();
$account=array();
$query="SELECT *,UNIX_TIMESTAMP(`date_added`) as `date_added` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=$uid";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=array_merge($output,mysql_fetch_assoc($res));
	$output['date_added']=strftime($config['datetime_format'],$output['date_added']+$config['time_offset']);
	if (empty($output['del'])) {
		unset($output['del']);
	}
	$output=array_merge($output,get_user_stats($output['fk_user_id'],array('profile_comments','blog_posts','total_photos')));
	if (empty($output['profile_comments'])) {
		unset($output['profile_comments']);
	}
	if (empty($output['blog_posts'])) {
		unset($output['blog_posts']);
	}
	if (empty($output['total_photos'])) {
		unset($output['total_photos']);
	}
	$c=0;
	foreach ($_pcats as $pcat_id=>$pcat) {
		$categs[$c]['pcat_name']=$pcat['pcat_name'];
		$categs[$c]['pcat_id']=$pcat_id;
		$cat_content=array();
		for ($i=0;isset($pcat['fields'][$i]);++$i) {
			$field=$_pfields[$pcat['fields'][$i]];
			$cat_content[$i]['label']=$field['label'];
			$cat_content[$i]['dbfield']=$field['dbfield'];
			switch ($field['field_type']) {

				case FIELD_TEXTFIELD:
					$cat_content[$i]['field']=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					break;

				case FIELD_TEXTAREA:
					$cat_content[$i]['field']=sanitize_and_format($output[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					if ($config['bbcode_profile']) {
						$cat_content[$i]['field']=bbcode2html($cat_content[$i]['field']);
					}
					break;

				case FIELD_SELECT:
					// if we sanitize here " will be rendered as &quot; which is not what we want
					//$cat_content[$i]['field']=sanitize_and_format($field['accepted_values'][$output[$field['dbfield']]],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					$cat_content[$i]['field']=isset($field['accepted_values'][$output[$field['dbfield']]]) ? $field['accepted_values'][$output[$field['dbfield']]] : '?';
					break;

				case FIELD_CHECKBOX_LARGE:
					$cat_content[$i]['field']=sanitize_and_format(vector2string_str($field['accepted_values'],$output[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					break;

				case FIELD_DATE:
					if ($output[$field['dbfield']]=='0000-00-00') {
						$output[$field['dbfield']]='?';
					}
					$cat_content[$i]['field']=$output[$field['dbfield']];
					break;

				case FIELD_LOCATION:
					$cat_content[$i]['field']=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$output[$field['dbfield'].'_country'],'-');
					if (!empty($output[$field['dbfield'].'_state'])) {
						$cat_content[$i]['field'].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$output[$field['dbfield'].'_state'],'-');
					}
					if (!empty($output[$field['dbfield'].'_city'])) {
						$cat_content[$i]['field'].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$output[$field['dbfield'].'_city'],'-');
					}
					break;

			}
		}
		$categs[$c]['cat_content']=$cat_content;
		++$c;
	}
	if (!empty($output['_photo']) && is_file(_PHOTOPATH_.'/t1/'.$output['_photo']) && is_file(_PHOTOPATH_.'/t2/'.$output['_photo']) && is_file(_PHOTOPATH_.'/'.$output['_photo'])) {
		$output['has_photo']=true;
	}
	if ($output['status']==STAT_PENDING) {
		$output['pending']=true;
	} elseif ($output['status']==STAT_EDIT) {
		$output['need_edit']=true;
	} elseif ($output['status']==STAT_APPROVED) {
		$output['approved']=true;
	}

	$query="SELECT a.`email`,UNIX_TIMESTAMP(a.`last_activity`) as `last_activity`,a.`status`,a.`skin`,b.`m_name` as `membership` FROM `".USER_ACCOUNTS_TABLE."` a,`{$dbtable_prefix}memberships` b WHERE a.`membership`=b.`m_value` AND a.`".USER_ACCOUNT_ID."`=$uid";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$account=mysql_fetch_assoc($res);
	$account['last_activity']=strftime($config['datetime_format'],$account['last_activity']+$config['time_offset']);
	$account['status']=vector2options($accepted_astats,$account['status']);
	$account['skin']=dbtable2options("`{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b",'a.`module_code`','b.`config_value`','b.`config_value`',$account['skin'],"a.`module_code`=b.`fk_module_code` AND a.`module_type`=".MODULE_SKIN." AND b.`config_option`='skin_name'");
	$query="SELECT UNIX_TIMESTAMP(`paid_until`) as `paid_until` FROM `{$dbtable_prefix}payments` WHERE `fk_user_id`=$uid AND `is_subscr`=1 AND `is_active`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$account['paid_until']=mysql_result($res,0,0);
		if ($account['paid_until']==0) {
			$account['paid_until']='FOREVER';
		} else {
			$account['paid_until']=strftime($config['datetime_format'],$account['paid_until']+$config['time_offset']);
		}
	} else {
		$account['paid_until']='-';
	}
}

$output['pic_width']=get_site_option('pic_width','core_photo');

if (empty($output['search_md5'])) {
	unset($output['search_md5']);
}
if (isset($_GET['o'])) {
	$output['o']=$_GET['o'];
}
if (isset($_GET['r'])) {
	$output['r']=$_GET['r'];
}
$output['return2me']='profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
if (isset($_GET['return'])) {
	$output['return2']=sanitize_and_format($_GET['return'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
}
$tpl->set_file('content','profile.html');
$tpl->set_loop('categs',$categs);
$tpl->set_var('output',$output);
$tpl->set_var('account',$account);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

$tplvars['title']=sprintf('%1$s Member Profile',$output['_user']);
$tplvars['css']='profile.css';
$tplvars['page']='profile';
include 'frame.php';

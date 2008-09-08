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

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/admin_functions.inc.php';
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

$config=get_site_option(array('datetime_format','time_offset'),'def_user_prefs');

$categs=array();
$account=array();
$query="SELECT `fk_user_id`,`_photo`,`_user`,`alt_url`,`rad_longitude`,`rad_latitude`,`score`,`status`,`reject_reason`,UNIX_TIMESTAMP(`date_added`) as `date_added`,`del`";
foreach ($_pfields as $field_id=>$field) {
	if ($field->config['visible']) {
		$query.=','.$field->query_select();
	}
}
$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=$uid";
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
	// set all the fields to their real (readable) values
	foreach ($_pfields as $field_id=>$field) {
		if ($field->config['visible']) {
			$_pfields[$field_id]->set_value($output,false);
		}
	}
	$c=0;
	foreach ($_pcats as $pcat_id=>$pcat) {
		$categs[$c]['pcat_name']=$pcat['pcat_name'];
		$categs[$c]['pcat_id']=$pcat_id;
		$cat_content=array();
		for ($i=0;isset($pcat['fields'][$i]);++$i) {
			$field=&$_pfields[$pcat['fields'][$i]];
			if ($field->config['visible']) {
				$cat_content[]=array('field'=>$field->display(),'label'=>$field->config['label'],'dbfield'=>$field->config['dbfield']);
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
	$query="SELECT DISTINCT `ip` FROM `{$dbtable_prefix}site_log` WHERE `fk_user_id`=".$output['fk_user_id']." OR `user`='".$output['_user']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$output['ips']=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$output['ips'][]=long2ip(mysql_result($res,$i,0));
	}
	$output['ips']=join(', ',$output['ips']);
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

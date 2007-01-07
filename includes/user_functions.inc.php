<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/user_functions.inc.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

include 'logs.inc.php';

function get_userid_by_user($user) {
	$myreturn=0;
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	if (!empty($user)) {
		$query="SELECT `user_id` FROM `{$dbtable_prefix}user_accounts` WHERE `user`='$user'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


function get_user_by_userid($user_id) {
	$myreturn='';
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	if (!empty($user_id)) {
		$query="SELECT `user` FROM `{$dbtable_prefix}user_accounts` WHERE `user_id`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


// copy of the admin_functions.inc.php: get_site_option()
// make sure they're synchronized
function get_site_option($option,$module_code) {
	$myreturn=0;
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="SELECT `config_option`,`config_value` FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code`='$module_code'";
	if (is_array($option)) {
		if (!empty($option)) {
			$query.=" AND `config_option` IN ('".join("','",$option)."')";
		}
	} else {
		$query.=" AND `config_option`='$option'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=array();
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
		if (is_string($option)) {
			$myreturn=array_shift($myreturn);
		}
	}
	return $myreturn;
}


function set_site_option($option,$module_code,$value) {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`='$value' WHERE `config_option`='$option' AND `fk_module_code`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function check_login_member($level_id) {
	$topass=array();
	if (!($GLOBALS['_access_level'][$level_id]&1) && (!isset($_SESSION['user']['user_id']) || empty($_SESSION['user']['user_id']))) {
		$mysession=session_id();
		if (empty($mysession)) {
			session_start();
		}
		$_SESSION['timedout']=array('url'=>(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']),'method'=>$_SERVER['REQUEST_METHOD'],'qs'=>($_SERVER['REQUEST_METHOD']=='GET' ? $_GET : $_POST));
		redirect2page('login.php');
	}
	if (!isset($GLOBALS['_access_level'][$level_id])) {
		$GLOBALS['_access_level'][$level_id]=0;
	}
	if (($GLOBALS['_access_level'][$level_id]&$_SESSION['user']['membership'])!=$_SESSION['user']['membership']) {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][3];
		redirect2page('info.php',$topass);
	}
}

function get_module_stats($module_code,$user_id=0,$stat='') {
	$myreturn=array();
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	if (!empty($user_id)) {
		$query="SELECT `stat`,`value` FROM `{$dbtable_prefix}user_stats` WHERE `fk_user_id`='$user_id' AND `fk_module_code`='$module_code'";
		if (!empty($stat)) {
			$query.=" AND `stat`='$stat'";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
	}
	return $myreturn;
}

function get_user_settings($user_id,$module_code) {
	$myreturn=array();
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	if (!empty($user_id)) {
		$query="SELECT `config_option`,`config_value` FROM `{$dbtable_prefix}user_settings2` WHERE `fk_user_id`='$user_id' AND `fk_module_code`='$module_code'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
	}
	return $myreturn;
}


function allow_at_level($level_id,$membership=1) {
	$myreturn=false;
	$membership=(int)$membership;
	if (($GLOBALS['_access_level'][$level_id]&((int)$membership))==$membership) {
		$myreturn=true;
	}
	return $myreturn;
}


function get_user_folder_name($folder_id,$user_id=null) {
	$myreturn='';
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="SELECT `folder` FROM `{$dbtable_prefix}user_folders` WHERE `folder_id`='$folder_id'";
	if (isset($user_id)) {
		$query.=" AND `fk_user_id`='$user_id'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}


function get_cache_user_mini($user_ids,$skin) {
	$myreturn=array();
	for ($i=0;isset($user_ids[$i]);++$i) {
		$file=_BASEPATH_.'/skins/'.$skin.'/cache/users/'.$user_ids[$i]{0}.'/'.$user_ids[$i].'/details_gallery.html';
		if (is_file($file)) {
			$myreturn[$i]['user']=fread($fp=fopen($file,'rb'),filesize($file));
		}
	}
	return $myreturn;
}


function update_location($user_id,$field,$field_name='') {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$latitude=0;
	$longitude=0;
	$state_id=0;
	$city_id=0;
	if (isset($field['city']) && !empty($field['city']) && (!isset($field['zip']) || empty($field['zip']))) {
		$query="SELECT `latitude`,`longitude` FROM `{$dbtable_prefix}loc_cities` WHERE `city_id`='".$field['city']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($latitude,$longitude)=mysql_fetch_row($res);
		}
	} elseif (isset($field['zip']) && !empty($field['zip'])) {
		$query="SELECT `latitude`,`longitude`,`fk_state_id`,`fk_city_id` FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$field['zip']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($latitude,$longitude,$state_id,$city_id)=mysql_fetch_row($res);
		}
	}
	if (!empty($latitude) || !empty($longitude) || !empty($state_id) || !empty($city_id)) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET ";
		if (!empty($latitude)) {
			$query.="`latitude`='$latitude',";
		}
		if (!empty($longitude)) {
			$query.="`longitude`='$longitude',";
		}
		if (!empty($state_id)) {
			$query.="`{$field_name}_state`='$state_id',";
		}
		if (!empty($state_id)) {
			$query.="`{$field_name}_city`='$city_id',";
		}
		$query=substr($query,0,-1)." WHERE `fk_user_id`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

function get_module_codes_by_type($module_type) {
	$myreturn=array();
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='$module_type'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}
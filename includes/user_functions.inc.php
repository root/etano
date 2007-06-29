<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/user_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

include 'logs.inc.php';
include 'site_bans.inc.php';
$_access_level=array();
require_once 'access_levels.inc.php';
require_once 'general_functions.inc.php';
$tplvars['tplurl']=_BASEURL_.'/skins_site/'.get_my_skin();
$tplvars['tplrelpath']=$GLOBALS['relative_path'].'skins_site/'.get_my_skin();
$GLOBALS['_lang']=array();
require_once $tplvars['tplrelpath'].'/lang/strings.inc.php';
$_pfields=array();
$_pcats=array();
require_once 'fields.inc.php';
if (!isset($_SESSION['user']['user_id'])) {
	$_SESSION['user']['user']='guest';
	$_SESSION['user']['membership']=1;
} else {
	$tplvars['user_logged']=true;
}
$tplvars['myself']=$_SESSION['user'];
$GLOBALS['_list_of_online_members']=get_online_ids();
$GLOBALS['page_last_modified_time']=isset($_SESSION['user']['loginout']) ? $_SESSION['user']['loginout'] : 0;	// need this for cache control - the Last-Modified header

if (!isset($_SESSION['user']['prefs'])) {
	$_SESSION['user']['prefs']=get_site_option(array('date_format','datetime_format','time_offset'),'def_user_prefs');
}

if (function_exists('error_handler')) {
	set_error_handler('error_handler');
} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
}

function get_userid_by_user($user) {
	$myreturn=0;
	if (!empty($user)) {
		$query="SELECT `".USER_ACCOUNT_ID."` FROM ".USER_ACCOUNTS_TABLE." WHERE `".USER_ACCOUNT_USER."`='$user'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


function check_login_member($level_code) {
	// is this user banned?
	global $_bans;
	if (isset($_bans[_PUNISH_BANUSER_]) && in_array($_SESSION['user']['user'],$_bans[_PUNISH_BANUSER_])) {
		die;
	} elseif (isset($_bans[_PUNISH_BANIP_]) && in_array(sprintf('%u',ip2long($_SERVER['REMOTE_ADDR'])),$_bans[_PUNISH_BANIP_])) {
		die;
	}
	global $dbtable_prefix;
	if (!isset($GLOBALS['_access_level'][$level_code])) {
		$GLOBALS['_access_level'][$level_code]=0;	// no access allowed if level not defined
	}
	// ask visitors to login if they land on a page that doesn't allow guests
	if (!($GLOBALS['_access_level'][$level_code]&1) && (!isset($_SESSION['user']['user_id']) || empty($_SESSION['user']['user_id']))) {
		$mysession=session_id();
		if (empty($mysession)) {
			session_start();
		}
		$_SESSION['timedout']=array('url'=>(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']),'method'=>$_SERVER['REQUEST_METHOD'],'qs'=>($_SERVER['REQUEST_METHOD']=='GET' ? $_GET : $_POST));
		redirect2page('login.php');
	}
//	unset($_SESSION['timedout']);
	if (($GLOBALS['_access_level'][$level_code]&$_SESSION['user']['membership'])!=$_SESSION['user']['membership']) {
		redirect2page('info.php',array(),'type=access');	// no access to this feature
	}
	$user_id=0;
	$now=gmdate('YmdHis');
	if (isset($_SESSION['user']['user_id'])) {
		$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `last_activity`='$now' WHERE `".USER_ACCOUNT_ID."`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$user_id=$_SESSION['user']['user_id'];
	}
	$query="UPDATE `{$dbtable_prefix}online` SET `last_activity`='$now' WHERE `fk_user_id`='$user_id' AND `sess`='".session_id()."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (!mysql_affected_rows()) {
		$query="REPLACE INTO `{$dbtable_prefix}online` SET `fk_user_id`='$user_id',`sess`='".session_id()."',`last_activity`='$now'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
	// log and rate limit
	$log['level']=$level_code;
	$log['user_id']=isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0;
	$log['sess']=session_id();
	$log['user']=$_SESSION['user']['user'];
	$log['membership']=$_SESSION['user']['membership'];
	$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
	if ($level_code!='all') {
		log_user_action($log);
	}
	rate_limiter($log);
}


function allow_at_level($level_code,$membership=1) {
	$myreturn=false;
	$membership=(int)$membership;
	if (isset($GLOBALS['_access_level'][$level_code]) && ($GLOBALS['_access_level'][$level_code]&$membership)==$membership) {
		$myreturn=true;
	}
	return $myreturn;
}


function get_user_folder_name($folder_id,$user_id=null) {
	$myreturn='';
	global $dbtable_prefix;
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

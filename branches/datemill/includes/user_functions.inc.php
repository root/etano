<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/user_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

include 'logs.inc.php';
include 'site_bans.inc.php';
$_access_level=array();
require_once 'access_levels.inc.php';
require_once 'general_functions.inc.php';
$tplvars['tplurl']=_BASEURL_.'/skins_site/'.get_my_skin();
$tplvars['tplrelpath']=$GLOBALS['relative_path'].'skins_site/'.get_my_skin();
$GLOBALS['_lang']=array();
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/global.inc.php';
$accepted_months=array('month','jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
$_pfields=array();
$_pcats=array();
require_once 'fields.inc.php';
if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	$_SESSION[_LICENSE_KEY_]['user']['user_id']=(int)$_SESSION[_LICENSE_KEY_]['user']['user_id'];
	$tplvars['user_logged']=true;
} else {
	$_SESSION[_LICENSE_KEY_]['user']['user']='guest';
	$_SESSION[_LICENSE_KEY_]['user']['membership']=1;
}
$tplvars['myself']=$_SESSION[_LICENSE_KEY_]['user'];
$GLOBALS['page_last_modified_time']=isset($_SESSION[_LICENSE_KEY_]['user']['loginout']) ? $_SESSION[_LICENSE_KEY_]['user']['loginout'] : 0;	// need this for cache control - the Last-Modified header

if (!isset($_SESSION[_LICENSE_KEY_]['user']['prefs'])) {
	$_SESSION[_LICENSE_KEY_]['user']['prefs']=get_site_option(array('date_format','datetime_format','time_offset'),'def_user_prefs');
}

if (function_exists('error_handler')) {
	set_error_handler('error_handler');
} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
}

function error_handler($errlevel,$message,$file='unset',$line='unset') {
	$error=array();
	$error['text']=$message."\n<br />";
	if (!empty($GLOBALS['query'])) {
		$error['text'].='Last query run: '.$GLOBALS['query']."\n<br />";
	}
	ob_start();
	echo '<pre>';
	print_r(debug_backtrace());
	echo '</pre>';
	$error['text'].=ob_get_contents();
	ob_end_clean();

	require_once _BASEPATH_.'/includes/classes/log_error.class.php';
	new log_error($error);
	if ($errlevel==E_USER_ERROR) {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Sorry, there has been an error processing your request. For more info regarding Etano community builder or other dating and social networking products please see the links above.';
		redirect2page('info.php',$topass);
		exit;
	}
}


function get_userid_by_user($user) {
	$myreturn=0;
	if (!empty($user)) {
		$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_USER."`='$user'";
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
	if (isset($_bans[_PUNISH_BANUSER_]) && in_array($_SESSION[_LICENSE_KEY_]['user']['user'],$_bans[_PUNISH_BANUSER_])) {
		die;
	} elseif (isset($_bans[_PUNISH_BANIP_]) && in_array(sprintf('%u',ip2long($_SERVER['REMOTE_ADDR'])),$_bans[_PUNISH_BANIP_])) {
		die;
	}
	global $dbtable_prefix;
	if (!isset($GLOBALS['_access_level'][$level_code])) {
		$GLOBALS['_access_level'][$level_code]=0;	// no access allowed if level not defined
	}
	// ask visitors to login if they land on a page that doesn't allow guests
	if (!($GLOBALS['_access_level'][$level_code]&1) && empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
		$_SESSION[_LICENSE_KEY_]['user']['timedout']=array('url'=>(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']),'method'=>$_SERVER['REQUEST_METHOD'],'qs'=>($_SERVER['REQUEST_METHOD']=='GET' ? $_GET : $_POST));
		redirect2page('login.php');
	}
//	unset($_SESSION[_LICENSE_KEY_]['user']['timedout']);
	if (($GLOBALS['_access_level'][$level_code]&$_SESSION[_LICENSE_KEY_]['user']['membership'])!=$_SESSION[_LICENSE_KEY_]['user']['membership']) {
		redirect2page('info.php',array(),'type=access');	// no access to this feature
	}
	$user_id=0;
	$now=gmdate('YmdHis');
	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
		$_SESSION[_LICENSE_KEY_]['user']['user_id']=(int)$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		$user_id=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
	}
	// log and rate limit
	$log['level']=$level_code;
	$log['user_id']=$user_id;
	$log['sess']=session_id();
	$log['user']=$_SESSION[_LICENSE_KEY_]['user']['user'];
	$log['membership']=$_SESSION[_LICENSE_KEY_]['user']['membership'];
	$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
	if ($level_code!='all') {
		log_user_action($log);
		rate_limiter($log);
	}
}


function get_user_folder_name($folder_id,$user_id=null) {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT `folder` FROM `{$dbtable_prefix}user_folders` WHERE `folder_id`=$folder_id";
	if (isset($user_id)) {
		$query.=" AND `fk_user_id`=$user_id";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	return $myreturn;
}


function get_my_skin() {
	if (!empty($_SESSION[_LICENSE_KEY_]['user']['skin']) && is_dir(_BASEPATH_.'/skins_site/'.$_SESSION[_LICENSE_KEY_]['user']['skin'])) {
		$myreturn=$_SESSION[_LICENSE_KEY_]['user']['skin'];
		$_COOKIE['sco_app']['skin']=$myreturn;
	} elseif (!empty($_COOKIE['sco_app']['skin']) && preg_match('/^\w+$/',$_COOKIE['sco_app']['skin']) && is_dir(_BASEPATH_.'/skins_site/'.$_COOKIE['sco_app']['skin'])) {
		$myreturn=$_COOKIE['sco_app']['skin'];
		// save the option in less expensive places
		$_SESSION[_LICENSE_KEY_]['user']['skin']=$myreturn;
	} else {
		$myreturn=get_default_skin_dir();
		// save the option in less expensive places
		$_COOKIE['sco_app']['skin']=$myreturn;
		$_SESSION[_LICENSE_KEY_]['user']['skin']=$myreturn;
	}
	return $myreturn;
}

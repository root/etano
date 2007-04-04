<?php
/******************************************************************************
newdsb
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
$_access_level=array();
require_once 'access_levels.inc.php';
require_once 'general_functions.inc.php';
$tplvars['tplurl']=_BASEURL_.'/skins_site/'.get_my_skin();
$tplvars['tplrelpath']=$tplvars['relative_path'].'skins_site/'.get_my_skin();
$_lang=array();
require_once $tplvars['tplrelpath'].'/lang/strings.inc.php';
$_pfields=array();
$_pcats=array();
require_once 'fields.inc.php';

if (function_exists('error_handler')) {
	set_error_handler('error_handler');
} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
}

function get_userid_by_user($user) {
	$myreturn=0;
	if (!empty($user)) {
		$query="SELECT `user_id` FROM ".USER_ACCOUNTS_TABLE." WHERE `user`='$user'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


function get_user_by_userid($user_id) {
	$myreturn='';
	if (!empty($user_id)) {
		$query="SELECT `user` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


function check_login_member($level_id) {
	$topass=array();
	global $dbtable_prefix;
	if (!isset($GLOBALS['_access_level'][$level_id])) {
		$GLOBALS['_access_level'][$level_id]=0;	// no access allowed if level not defined
	}
	// ask visitors to login if they land on a page that doesn't allow guests
	if (!($GLOBALS['_access_level'][$level_id]&1) && (!isset($_SESSION['user']['user_id']) || empty($_SESSION['user']['user_id']))) {
		$mysession=session_id();
		if (empty($mysession)) {
			session_start();
		}
		$_SESSION['timedout']=array('url'=>(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']),'method'=>$_SERVER['REQUEST_METHOD'],'qs'=>($_SERVER['REQUEST_METHOD']=='GET' ? $_GET : $_POST));
		redirect2page('login.php');
	}
//	unset($_SESSION['timedout']);
	// members from here on
	if (($GLOBALS['_access_level'][$level_id]&$_SESSION['user']['membership'])!=$_SESSION['user']['membership']) {
		$topass['message']['type']=MESSAGE_ERROR;
//		$topass['message']['text']=$GLOBALS['_lang'][3];
		$topass['message']['text']="We're sorry but you don't have access to this feature. --link to payment--";//translate
		redirect2page('info.php',$topass);
	}
	$user_id=0;
	if (isset($_SESSION['user']['user_id'])) {
		$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `last_activity`=now() WHERE `user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$user_id=$_SESSION['user']['user_id'];
	}
	$query="REPLACE INTO `{$dbtable_prefix}online` SET `fk_user_id`='$user_id',`sess`='".session_id()."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function get_user_stats($user_id,$stat='') {
	$myreturn=array();
	if (is_array($stat)) {
		for ($i=0;isset($stat[$i]);++$i) {
			$myreturn[$stat[$i]]=0;
		}
	} else {
		$myreturn[$stat]=0;
	}
	global $dbtable_prefix;
	if (!empty($user_id)) {
		$query="SELECT `stat`,`value` FROM `{$dbtable_prefix}user_stats` WHERE `fk_user_id`='$user_id'";
		if (!empty($stat)) {
			if (is_array($stat)) {
				$query.=" AND `stat` IN ('".join("','",$stat)."')";
			} else {
				$query.=" AND `stat`='$stat'";
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
	}
	return $myreturn;
}


function update_stats($user_id,$stat,$add_val) {
	global $dbtable_prefix;
	$query="UPDATE `{$dbtable_prefix}user_stats` SET `value`=`value`+$add_val WHERE `fk_user_id`='$user_id' AND `stat`='$stat' LIMIT 1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (!mysql_affected_rows()) {
		$query="INSERT INTO `{$dbtable_prefix}user_stats` SET `fk_user_id`='$user_id',`stat`='$stat',`value`='$add_val'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}


function get_user_settings($user_id,$module_code) {
	$myreturn=array();
	global $dbtable_prefix;
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


function add_member_score($user_ids,$act,$times=1,$points=0) {
	if (!is_array($user_ids)) {
		$user_ids=array($user_ids);
	}
	global $dbtable_prefix;
	$scores=array('force'=>0,'login'=>5,'logout'=>-4,'approved'=>10,'rejected'=>-10,'add_main_photo'=>10,'del_main_photo'=>-10,'add_photo'=>2,'del_photo'=>-2,'add_blog'=>5,'payment'=>50,'unpayment'=>-50,);
	$scores['force']+=$points;
	if (isset($scores[$act]) && !empty($user_ids)) {
		$scores[$act]*=$times;
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `score`=`score`+".$scores[$act]." WHERE `fk_user_id` IN ('".join("','",$user_ids)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

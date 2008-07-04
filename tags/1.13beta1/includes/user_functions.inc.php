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

if (!defined('_LICENSE_KEY_')) {
	die('Hacking attempt');
}

include _BASEPATH_.'/includes/logs.inc.php';
include _BASEPATH_.'/includes/site_bans.inc.php';
$_access_level=array();
require _BASEPATH_.'/includes/access_levels.inc.php';
require_once _BASEPATH_.'/includes/general_functions.inc.php';
$tplvars['tplurl']=_BASEURL_.'/skins_site/'.get_my_skin();
$tplvars['tplrelpath']=$GLOBALS['relative_path'].'skins_site/'.get_my_skin();
$GLOBALS['_lang']=array();
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/global.inc.php';
$accepted_months=array($GLOBALS['_lang'][166],$GLOBALS['_lang'][167],$GLOBALS['_lang'][168],$GLOBALS['_lang'][169],$GLOBALS['_lang'][170],$GLOBALS['_lang'][171],$GLOBALS['_lang'][172],$GLOBALS['_lang'][173],$GLOBALS['_lang'][174],$GLOBALS['_lang'][175],$GLOBALS['_lang'][176],$GLOBALS['_lang'][177],$GLOBALS['_lang'][178]);
$_pfields=array();
$_pcats=array();
require _BASEPATH_.'/includes/fields.inc.php';
if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	$_SESSION[_LICENSE_KEY_]['user']['user_id']=(int)$_SESSION[_LICENSE_KEY_]['user']['user_id'];
	$tplvars['user_logged']=true;
} else {
	$_SESSION[_LICENSE_KEY_]['user']['user']='guest';
	$_SESSION[_LICENSE_KEY_]['user']['membership']=1;
}
$tplvars['myself']=$_SESSION[_LICENSE_KEY_]['user'];
$GLOBALS['_list_of_online_members']=get_online_ids();
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
		$topass['message']['text']='Sorry, there has been an error processing your request. Please try again or notify the webmaster about the problem.';
		redirect2page('info.php',$topass);
		exit;
	}
}


function get_userid_by_user($user) {
	$myreturn=0;
	global $dbtable_prefix;
	if (!empty($user)) {
		$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `_user`='$user'";
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
	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $_SESSION[_LICENSE_KEY_]['user']['pstat']<STAT_APPROVED && empty($GLOBALS['_allow_na'][$level_code])) {
		redirect2page('info.php',array(),'type=profile_na');	// no access to this feature until the profile gets approved
	}
	$user_id=!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) ? $_SESSION[_LICENSE_KEY_]['user']['user_id'] : 0;
	if (USE_DB_SESSIONS==0) {
		$now=gmdate('YmdHis');
		$query="UPDATE `{$dbtable_prefix}online` SET `last_activity`='$now' WHERE `fk_user_id`=$user_id AND `sess`='".session_id()."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_affected_rows()) {
			$query="REPLACE INTO `{$dbtable_prefix}online` SET `fk_user_id`=$user_id,`sess`='".session_id()."',`last_activity`='$now'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
	// log and rate limit
	$log['level']=$level_code;
	$log['user_id']=$user_id;
	$log['sess']=session_id();
	$log['user']=$_SESSION[_LICENSE_KEY_]['user']['user'];
	$log['membership']=$_SESSION[_LICENSE_KEY_]['user']['membership'];
	$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
	if ($level_code!='all' && $level_code!='auth') {
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


/**
 *	Creates the tpl loop to show comments and the textarea where new comments could be written. Handles the cases when user is
 *	not logged in or not allowed to post comments.
 *
 *	@access public
 *	@param string $type the identifier for the item where comments are displayed. Can be one of 'user','photo','blog','video'
 *	@param int $parent_id the ID in the parent table of the item where these comments are posted.
 *	@param array $config reference to the $config array in the calling script. It needs 'use_captcha','bbcode_comments','smilies_comm'
 *	@param array $output reference to the $output array in the calling script. It injects additional variables in $output to be
 *		used by the template system.
 *
 */
function create_comments_loop($type,$parent_id,&$output) {
	global $dbtable_prefix,$__field2format,$_list_of_online_members,$page_last_modified_time;
	$myreturn=array();
	switch ($type) {
		case 'user':
			$table="{$dbtable_prefix}comments_profile";
			$allow_comments=(!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $_SESSION[_LICENSE_KEY_]['user']['user_id']==$parent_id) ? $_SESSION[_LICENSE_KEY_]['user']['prefs']['profile_comments'] : get_user_settings($parent_id,'def_user_prefs','profile_comments');
			break;

		case 'blog':
			$table="{$dbtable_prefix}comments_blog";
			$allow_comments=isset($output['allow_comments']) ? $output['allow_comments'] : 1;
			break;

		case 'photo':
			$table="{$dbtable_prefix}comments_photo";
			$allow_comments=isset($output['allow_comments']) ? $output['allow_comments'] : 1;
			break;

		case 'video':
			$table="{$dbtable_prefix}comments_video";
			$allow_comments=isset($output['allow_comments']) ? $output['allow_comments'] : 1;
			break;
	}

	$config=get_site_option(array('use_captcha','bbcode_comments','smilies_comm'),'core');
	$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);
	$query="SELECT a.`comment_id`,a.`comment`,a.`fk_user_id`,a.`_user` as `user`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`_photo` as `photo` FROM `$table` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_parent_id`=$parent_id AND a.`status`=".STAT_APPROVED." ORDER BY a.`comment_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['date_posted']>$page_last_modified_time) {
			$page_last_modified_time=$rsrow['date_posted'];
		}
		// if someone has asked to edit his/her comment
		if ($edit_comment==$rsrow['comment_id']) {
			$output['comment_id']=$rsrow['comment_id'];
			$output['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		}
		$rsrow['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$rsrow['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
		$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (!empty($config['bbcode_comments'])) {
			$rsrow['comment']=bbcode2html($rsrow['comment']);
		}
		if (!empty($config['smilies_comm'])) {
			$rsrow['comment']=text2smilies($rsrow['comment']);
		}
		// allow showing the edit links to rightfull owners
		if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $rsrow['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
			$rsrow['editme']=true;
		}

		if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
			unset($rsrow['fk_user_id']);
		} else {
			if (isset($_list_of_online_members[$rsrow['fk_user_id']])) {
				$rsrow['is_online']='is_online';
				$rsrow['user_online_status']=$GLOBALS['_lang'][102];
			} else {
				$rsrow['user_online_status']=$GLOBALS['_lang'][103];
			}
		}
		if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$rsrow['photo']='no_photo.gif';
		}
		$myreturn[]=$rsrow;
	}
	if (!empty($myreturn)) {
		$output['num_comments']=count($myreturn);
		$output['show_comments']=true;
	}

	if ($allow_comments) {
		// may I post comments please?
		if (allow_at_level('write_comments',$_SESSION[_LICENSE_KEY_]['user']['membership'])) {
			$output['allow_comments']=true;
			if (empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
				if (!empty($config['use_captcha'])) {
					require _BASEPATH_.'/includes/classes/sco_captcha.class.php';
					$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
					$_SESSION['captcha_word']=$c->gen_rnd_string(4);
					$output['rand']=make_seed();
					$output['use_captcha']=true;
				}
			}
			// would you let me use bbcode?
			if (!empty($config['bbcode_comments'])) {
				$output['bbcode_comments']=true;
			}
			// if we came back after an error get what was previously posted
			if (isset($_SESSION['topass']['input'])) {
				$output=array_merge($output,$_SESSION['topass']['input']);
				unset($_SESSION['topass']['input']);
			}
		} else {
			unset($output['allow_comments']);
		}
	} else {
		unset($output['allow_comments']);
	}
	if (!empty($edit_comment)) {
		$_SERVER['QUERY_STRING']=str_replace('&edit_comment='.$edit_comment,'',$_SERVER['QUERY_STRING']);
	}
	return $myreturn;
}

<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/login.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/login.inc.php';

if (is_file(_BASEPATH_.'/events/processors/login.php')) {
	include _BASEPATH_.'/events/processors/login.php';
}

$score_threshold=600;	// seconds
$error=false;
$topass=array();
$nextpage='login.php';
$qs='';
$qssep='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=strtolower(sanitize_and_format_gpc($_POST,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
	$pass=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($user) && !empty($pass)) {
		$log['level']='login';
		$log['user_id']=!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) ? $_SESSION[_LICENSE_KEY_]['user']['user_id'] : 0;
		$log['sess']=session_id();
		$log['user']=$user;
		$log['membership']=$_SESSION[_LICENSE_KEY_]['user']['membership'];
		$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
		log_user_action($log);
		rate_limiter($log);
		$query="SELECT a.`".USER_ACCOUNT_ID."` as `user_id`,b.`_user` as `user`,a.`status`,a.`membership`,UNIX_TIMESTAMP(a.`last_activity`) as `last_activity`,a.`email`,b.`status` as `pstat` FROM `".USER_ACCOUNTS_TABLE."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` WHERE a.`".USER_ACCOUNT_USER."`='$user' AND a.`".USER_ACCOUNT_PASS."`=".PASSWORD_ENC_FUNC."('$pass')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$user=mysql_fetch_assoc($res);
			$user['membership']=(int)$user['membership'];
			$user['user_id']=(int)$user['user_id'];
			if ($user['status']==ASTAT_ACTIVE) {
				$time=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
				$user['prefs']=get_user_settings($user['user_id'],'def_user_prefs',array('date_format','datetime_format','time_offset','rate_my_photos','profile_comments'));
				if ($user['last_activity']<$time-$score_threshold) {
					add_member_score($user['user_id'],'login');
				}
				$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `last_activity`='".gmdate('YmdHis')."' WHERE `".USER_ACCOUNT_ID."`=".$user['user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (USE_DB_SESSIONS==1) {
					$query="UPDATE `{$dbtable_prefix}online` SET `fk_user_id`=".$user['user_id']." WHERE `sess`='".session_id()."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
				unset($user['last_activity'],$user['email']);
				$_SESSION[_LICENSE_KEY_]['user']=array_merge(isset($_SESSION[_LICENSE_KEY_]['user']) ? $_SESSION[_LICENSE_KEY_]['user'] : array(),$user);
				$_SESSION[_LICENSE_KEY_]['user']['loginout']=$time;
				if (isset($_on_after_login)) {
					for ($i=0;isset($_on_after_login[$i]);++$i) {
						call_user_func($_on_after_login[$i]);
					}
				}
				if (isset($_SESSION[_LICENSE_KEY_]['user']['timedout']['url'])) {
					$next=$_SESSION[_LICENSE_KEY_]['user']['timedout'];
					unset($_SESSION[_LICENSE_KEY_]['user']['timedout']);
					if ($next['method']=='GET') {
						redirect2page($next['url'].'?'.array2qs($next['qs']),array(),'',true);
					} else {
						post2page($next['url'],$next['qs'],true);
					}
				} else {
					$nextpage='home.php';
				}
			} elseif ($user['status']==ASTAT_UNVERIFIED) {
				$nextpage='info.php';
				$qs.=$qssep.'type=acctactiv&uid='.$user['user_id'].'&email='.$user['email'];
				$qssep='&';
			} elseif ($user['status']==ASTAT_SUSPENDED) {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']=$GLOBALS['_lang'][71];
			}
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$GLOBALS['_lang'][72];
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][72];
	}
}
redirect2page($nextpage,$topass,$qs);

<?php
class EtanoApi {

	static function load_common() {
		require_once dirname(__FILE__).'/../common.inc.php';
		require_once _BASEPATH_.'/includes/logs.inc.php';
		include_once _BASEPATH_.'/includes/site_bans.inc.php';
		require_once _BASEPATH_.'/includes/general_functions.inc.php';
	}

	static function login_by_id($user_id) {
		global $dbtable_prefix;
		EtanoApi::load_common();
		if (is_file(_BASEPATH_.'/events/processors/login.php')) {
			include_once _BASEPATH_.'/events/processors/login.php';
		}
		require_once _BASEPATH_.'/skins_site/'.EtanoApi::get_my_skin().'/lang/login.inc.php';
		$score_threshold=600;	// seconds
		$error=false;
		$topass=array();
		$nextpage='login.php';
		$qs='';
		$qssep='';
		$log['level']='login';
		$log['user_id']=$user_id;
		$log['sess']=session_id();
//		$log['user']=$user;
//		$log['membership']=$_SESSION[_LICENSE_KEY_]['user']['membership'];
		$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
		log_user_action($log);
		rate_limiter($log);
		$query="SELECT a.`".USER_ACCOUNT_ID."` as `user_id`,b.`_user` as `user`,a.`status`,a.`membership`,UNIX_TIMESTAMP(a.`last_activity`) as `last_activity`,a.`email`,b.`status` as `pstat` FROM `".USER_ACCOUNTS_TABLE."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` WHERE a.`".USER_ACCOUNT_ID."`=$user_id";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$user=mysql_fetch_assoc($res);
			$user['membership']=(int)$user['membership'];
			$user['user_id']=(int)$user['user_id'];
			if ($user['status']==ASTAT_ACTIVE) {
				$time=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
				$user['prefs']=get_user_settings($user['user_id'],'def_user_prefs',array('date_format','datetime_format','time_offset','rate_my_photos','profile_comments'));
				$score=0;
				// it might happen that the user is already logged in. Don't add the login score if that's the case.
				$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}online` WHERE `fk_user_id`=".$user['user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (!mysql_num_rows($res)) {
					$score+=add_member_score($user['user_id'],'login',1,true);	// just read the value
				}
				if ($user['last_activity']<$time-$score_threshold) {
					$score+=add_member_score($user['user_id'],'login_bonus',1,true);	// just read the value
				}
				if (!empty($score)) {
					add_member_score($user['user_id'],'force',1,false,$score);
				}
				$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `last_activity`='".gmdate('YmdHis')."' WHERE `".USER_ACCOUNT_ID."`=".$user['user_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				if (USE_DB_SESSIONS==1) {
					$query="REPLACE INTO `{$dbtable_prefix}online` SET `fk_user_id`=".$user['user_id'].",`sess`='".session_id()."',`sess_data`='".sanitize_and_format(serialize($_SESSION),TYPE_STRING,FORMAT_ADDSLASH)."'";
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
			} elseif ($user['status']==ASTAT_UNVERIFIED) {
				throw new Exception('',ASTAT_UNVERIFIED);
			} elseif ($user['status']==ASTAT_SUSPENDED) {
				throw new Exception($GLOBALS['_lang'][71],ASTAT_SUSPENDED);
			}
		} else {
			throw new Exception($GLOBALS['_lang'][72],0);
		}
		return true;
	}


	static function get_my_skin() {
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
}
?>
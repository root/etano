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

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

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
		$log['user_id']=isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0;
		$log['sess']=session_id();
		$log['user']=$user;
		$log['membership']=$_SESSION['user']['membership'];
		$log['ip']=sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
		log_user_action($log);
		rate_limiter($log);
		$query="SELECT a.`".USER_ACCOUNT_ID."` as `user_id`,a.`".USER_ACCOUNT_USER."` as `user`,a.`status`,a.`membership`,UNIX_TIMESTAMP(a.`last_activity`) as `last_activity`,a.`email` FROM ".USER_ACCOUNTS_TABLE." a WHERE a.`".USER_ACCOUNT_USER."`='$user' AND a.`".USER_ACCOUNT_PASS."`=".PASSWORD_ENC_FUNC."('$pass')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$user=mysql_fetch_assoc($res);
			$user['membership']=(int)$user['membership'];
			if ($user['status']==ASTAT_ACTIVE) {
				$user['prefs']=get_user_settings($user['user_id'],'def_user_prefs',array('date_format','datetime_format','time_offset','rate_my_photos','profile_comments'));
				if ($user['last_activity']<time()-$score_threshold) {
					add_member_score($user['user_id'],'login');
				}
				unset($user['last_activity'],$user['email']);
				$_SESSION['user']=$user;
				$_SESSION['user']['loginout']=time();
				if (isset($_SESSION['timedout']['url'])) {
					$next=$_SESSION['timedout'];
					unset($_SESSION['timedout']);
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
				$topass['message']['text']='Your account is suspended.';
			}
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid user name or password. Please try again.';
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid user name or password. Please try again.';
	}
}
redirect2page($nextpage,$topass,$qs);

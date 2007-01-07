<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/login.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$error=false;
$topass=array();
$nextpage='login.php';
$qs='';
$qssep='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=sanitize_and_format_gpc($_POST,'user',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$pass=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	if (!empty($user) && !empty($pass)) {
		$log['level']=1;
		$log['user_id']=isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0;
		$log['user']=$user;
		$log['membership']=isset($_SESSION['user']['membership']) ? $_SESSION['user']['membership'] : 1;
		$log['ip']=$_SERVER['REMOTE_ADDR'];
		log_user_action($log);
		$redirect=rate_limiter($log);
		if ($redirect) {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$_lang[2];
			redirect2page('info.php',$topass);
		}
		$query="SELECT a.`user_id`,a.`user`,a.`status`,a.`membership` FROM `{$dbtable_prefix}user_accounts` a WHERE a.`user`='$user' AND a.`pass`=md5('$pass')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$user=mysql_fetch_assoc($res);
			$user['membership']=(int)$user['membership'];
// need to check status & rate_limiter.
			$user['prefs']=get_user_settings($user['user_id'],1);
			$_SESSION['user']=$user;
			if (isset($_SESSION['timedout']['url'])) {
				$page=$_SESSION['timedout']['url'];
				unset($_SESSION['timedout']['url']);
				redirect2page($page,array(),'',true);
			} else {
				$nextpage='home.php';
			}
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$_lang[1];
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$_lang[1];
	}
}
redirect2page($nextpage,$topass,$qs);
?>
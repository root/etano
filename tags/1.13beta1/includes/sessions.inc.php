<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/sessions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
*******************************************************************************/

if (!defined('_LICENSE_KEY_')) {
	die('Hacking attempt');
}

// _BASEPATH_ must be hardcoded below in order to allow a custom save path for session files.
// this would solve the 'permanent relogin issue' happening because the /tmp partition is full.
//ini_set('session.save_path',_BASEPATH_.'/tmp/sessions');
$cookie_domain='';
// set the cookie on the whole domain. If this is not desirable simply comment out the if below
if (isset($_SERVER['HTTP_HOST'])) {
	$cookie_domain=explode('.',$_SERVER['HTTP_HOST']);
	$is_ip=true;
	for ($i=0;isset($cookie_domain[$i]);++$i) {
		if ($cookie_domain[$i]!=((int)$cookie_domain[$i])) {
			$is_ip=false;
			break;
		}
	}
	if (!$is_ip) {
		if (count($cookie_domain)>2) {
			$cookie_domain='.'.$cookie_domain[count($cookie_domain)-2].'.'.$cookie_domain[count($cookie_domain)-1];
		} else {
			$cookie_domain='';
		}
		session_set_cookie_params(0,'/',$cookie_domain);
	} else {
		$cookie_domain='';
	}
}

if (defined('CACHE_LIMITER')) {
	session_cache_limiter(CACHE_LIMITER);
} else {
	session_cache_limiter('nocache');
}

if (defined('USE_DB_SESSIONS') && USE_DB_SESSIONS!=0) {
	session_name('dmsessid');
	unset($_GET['dmsessid'],$_POST['dmsessid']);
	function dm_session_open($save_path,$sess_name) {
		return true;
	}
	function dm_session_close() {
		return true;
	}
	function dm_session_read($sess_id) {
		global $dbtable_prefix;
		$myreturn='';
		if (preg_match('/^[A-Za-z0-9]{16,32}$/',$sess_id)) {
			$query="SELECT `sess_data` FROM `{$dbtable_prefix}online` WHERE `sess`='$sess_id' LIMIT 1";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$myreturn=mysql_result($res,0,0);
			}
		} else {
			$myreturn='';
		}
		return $myreturn;
	}
	function dm_session_write($sess_id,$sess_data) {
		global $dbtable_prefix;
		$myreturn=false;
		if (preg_match('/^[A-Za-z0-9]{16,32}$/',$sess_id)) {
			$sess_data=mysql_real_escape_string($sess_data);
			$now=gmdate('YmdHis');
			$query="UPDATE `{$dbtable_prefix}online` SET `last_activity`='$now',`sess_data`='$sess_data' WHERE `sess`='$sess_id'";
			$myreturn=@mysql_query($query);
			if ($myreturn && !mysql_affected_rows()) {
				$query="INSERT INTO `{$dbtable_prefix}online` SET `last_activity`='$now',`sess`='$sess_id',`sess_data`='$sess_data'";
				$myreturn=@mysql_query($query);
			}
		}
		return $myreturn;
	}
	function dm_session_destroy($sess_id) {
		global $dbtable_prefix;
		$myreturn=false;
		if (preg_match('/^[A-Za-z0-9]{16,32}$/',$sess_id)) {
			$query="DELETE FROM `{$dbtable_prefix}online` WHERE `sess`='$sess_id'";
			$myreturn=@mysql_query($query);
		}
		return $myreturn;
	}
	function dm_session_gc($max_lifetime) {
		// rely on the clean_online_table cron job.
		return true;
	}
	session_set_save_handler('dm_session_open', 'dm_session_close', 'dm_session_read', 'dm_session_write', 'dm_session_destroy', 'dm_session_gc');
}
session_start();
header('Content-Type: text/html; charset=utf-8',true);	// overwrite possible apache headers

if (isset($_GET['skin'])) {
	if (preg_match('/^\w+$/',$_GET['skin'])) {
		$_SESSION[_LICENSE_KEY_]['user']['skin']=$_GET['skin'];
		setcookie('sco_app[skin]',$_GET['skin'],mktime(0,0,0,date('m'),date('d'),date('Y')+1),'/',$cookie_domain);
		$GLOBALS['page_last_modified_time']=gmdate('YmdHis');
	}
} elseif (isset($_SESSION[_LICENSE_KEY_]['user']['skin']) && isset($_COOKIE['sco_app']['skin']) && $_COOKIE['sco_app']['skin']!=$_SESSION[_LICENSE_KEY_]['user']['skin']) {
// the cookie was probably set from javascript
	if (preg_match('/^\w+$/',$_COOKIE['sco_app']['skin'])) {
		$_SESSION[_LICENSE_KEY_]['user']['skin']=$_COOKIE['sco_app']['skin'];
		$GLOBALS['page_last_modified_time']=gmdate('YmdHis');
	}
}

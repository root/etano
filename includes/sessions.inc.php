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

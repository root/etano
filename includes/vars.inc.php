<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/vars.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
*******************************************************************************/

ini_set('include_path','.');
ini_set('session.use_cookies',1);
define('_DEBUG_',2);// Set to 0 for production! 0-No,1-Yes,2-Verbose. Used for debug in case of errors.
if (_DEBUG_!=0) {
	ini_set('error_reporting',2047);
	ini_set('display_errors',1);
} else {
	ini_set('display_errors',0);
}

// account status
define('ASTAT_SUSPENDED',5);
define('ASTAT_UNVERIFIED',10);
define('ASTAT_ACTIVE',15);

// profile status
define('PSTAT_PENDING',5);
define('PSTAT_EDIT',10);
define('PSTAT_APPROVED',15);

define('_ANY_',-1);
define('_CHOOSE_',-2);
define('_NDISCLOSED_',0);

// message types
define('MESS_MESS',0);	// regular messages
define('MESS_FLIRT',1);	// flirts
define('MESS_SYSTEM',2);	// admin messages

// search types
define('SEARCH_USER',1);
define('SEARCH_PHOTO',2);
define('SEARCH_BLOG',3);
define('SEARCH_TAG',4);

// module types
define('MODULE_REGULAR',0);
define('MODULE_PAYMENT',1);
define('MODULE_FRAUD',2);
define('MODULE_WIDGET',3);
define('MODULE_SKIN',4);

// filter types
define('FILTER_SENDER',1);
define('FILTER_SENDER_PROFILE',2);
define('FILTER_MESSAGE',3);

// fixed folders types
define('FOLDER_INBOX',0);
define('FOLDER_TRASH',-1);
define('FOLDER_OUTBOX',-2);
define('FOLDER_SPAMBOX',-3);

// flirt types
define('FLIRT_INIT',0);
define('FLIRT_REPLY',1);

// Unset globally registered vars
function _unset_vars(&$var) {
	$temp=array_keys($var);
	for ($i=0;isset($temp[$i]);++$i) {
		unset($GLOBALS[$temp[$i]]);
	}
}
if (ini_get('register_globals')=='1' || strtolower(ini_get('register_globals'))=='on') {
	$test=array('_GET','_POST','_SERVER','_COOKIE','_ENV','_SESSION','_REQUEST');
	foreach ($test as $k=>$var) {
		if (isset(${'HTTP'.$var.'_VARS'}) && is_array(${'HTTP'.$var.'_VARS'})) {
			_unset_vars(${'HTTP'.$var.'_VARS'});
			unset(${'HTTP'.$var.'_VARS'});
		}
		if (isset(${$var}) && is_array(${$var})) {
			_unset_vars(${$var});
			@reset(${$var});
		}
	}
	if (is_array(${'HTTP_POST_FILES'})) {
		_unset_vars(${'HTTP_POST_FILES'});
		@reset(${'HTTP_POST_FILES'});
	}
}

require_once 'defines.inc.php';
require_once 'sco_functions.inc.php';
define('HTML_LOCATION',107);
$__html2type[HTML_LOCATION]=TYPE_INT;
$__html2format[HTML_LOCATION]=0;
$_lang=array();
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/strings.inc.php';
$_pfields=array();
$_pcats=array();
require_once 'fields.inc.php';

$accepted_months=array($_lang[4],$_lang[7],$_lang[8],$_lang[9],$_lang[10],$_lang[11],$_lang[12],$_lang[13],$_lang[14],$_lang[15],$_lang[16],$_lang[17],$_lang[18]);
$accepted_currencies=array('USD'=>'USD','EUR'=>'EUR');
$tplvars['sitename']=_SITENAME_;
$tplvars['baseurl']=_BASEURL_;
$tplvars['photourl']=_PHOTOURL_;
if (isset($_SERVER['PHP_SELF'])) {
	$tplvars['relative_path']=@str_repeat('../',substr_count($_SERVER['PHP_SELF'],'/')-(substr_count(_BASEURL_,'/')-2)-1);
}
$tplvars['tplurl']=_BASEURL_.'/skins_site/'.get_my_skin();
$tplvars['tplrelpath']=$tplvars['relative_path'].'skins/'.get_my_skin();

$default_search_fields=array(1,2,3,4);

if (!isset($_SESSION['user']['user_id'])) {
	$_SESSION['user']['user']='guest';
	$_SESSION['user']['membership']=1;
} elseif (isset($_SESSION['user']['prefs'])) {
	$_user_settings=array_merge($_user_settings,$_SESSION['user']['prefs']);
}
if (isset($_SESSION['user'])) {
	$tplvars['myself']=$_SESSION['user'];
	if (isset($_SESSION['user']['user_id'])) {
		$tplvars['user_logged']=true;
	}
}

if (function_exists('error_handler')) {
	set_error_handler('error_handler');
} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
}
define('_CACHE_MODE_','disk');	// disk or db
define('_INTERNAL_VERSION_',001);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/common.inc.php
$Revision: 157 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
*******************************************************************************/

ob_start();
ini_set('include_path','.');
ini_set('session.use_cookies',1);
setlocale(LC_CTYPE,'C');
setlocale(LC_COLLATE,'C');
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

// items status
define('STAT_PENDING',5);
define('STAT_EDIT',10);
define('STAT_APPROVED',15);

define('_ANY_',-1);
define('_CHOOSE_',-2);
define('_NDISCLOSED_',0);

// message types
define('MESS_MESS',0);	// regular messages
define('MESS_FLIRT',1);	// flirts
define('MESS_SYSTEM',2);	// admin messages

// flirt types
define('FLIRT_INIT',0);
define('FLIRT_REPLY',1);

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

// default networks
define('NET_FRIENDS',1);
define('NET_BLOCK',2);
define('NET_FAVES',3);

// Unset globally registered vars. this should protect us agains any remote file inclusion attack
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

require_once 'sessions.inc.php';
require_once 'defines.inc.php';
require_once 'sco_functions.inc.php';
define('FIELD_LOCATION',107);
$__field2type[FIELD_LOCATION]=TYPE_INT;
$__field2format[FIELD_LOCATION]=0;
define('FIELD_RANGE',108);
require_once 'classes/phemplate.class.php';

// often used vars in skins
$tplvars['sitename']=_SITENAME_;
$tplvars['baseurl']=_BASEURL_;
$tplvars['photourl']=_PHOTOURL_;
$_ENV['lk']=md5(_LICENSE_KEY_);
if (isset($_SERVER['PHP_SELF'])) {
	$relative_path=@str_repeat('../',substr_count($_SERVER['PHP_SELF'],'/')-(substr_count(_BASEURL_,'/')-2)-1);
	$tplvars['relative_url']=@str_repeat('../',substr_count($_SERVER['REQUEST_URI'],'/')-(substr_count(_BASEURL_,'/')-2)-1);
}

//$accepted_months=array($_lang[4],$_lang[7],$_lang[8],$_lang[9],$_lang[10],$_lang[11],$_lang[12],$_lang[13],$_lang[14],$_lang[15],$_lang[16],$_lang[17],$_lang[18]);
$accepted_months=array('month','jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'); // translate
$accepted_currencies=array('USD'=>'USD','EUR'=>'EUR');

define('_INTERNAL_VERSION_',001);
$tplvars['js_lib_v']=1;	// change this when you update any .js file. This should force a reload of the js scripts.

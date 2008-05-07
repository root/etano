<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/common.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
*******************************************************************************/

ob_start();
ini_set('include_path','.');
ini_set('session.use_cookies',1);
ini_set('url_rewriter.tags', '');
ini_set('session.use_trans_sid',0);
ini_set('date.timezone','GMT');	// temporary fix for the php 5.1+ TZ compatibility
ini_set('error_reporting',2047);

setlocale(LC_CTYPE,'C');
setlocale(LC_COLLATE,'C');
define('_DEBUG_',0);// Set to 0 for production! 0-No,1-Yes,2-Verbose. Used for debug in case of errors.
if (_DEBUG_!=0) {
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
define('MODULE_3RD',5);

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

// activate db sessions?
define('USE_DB_SESSIONS',0);

require dirname(__FILE__).'/defines.inc.php';
require _BASEPATH_.'/includes/sco_functions.inc.php';
$etano_dblink=db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
if (!defined('NO_SESSION')) {
	require _BASEPATH_.'/includes/sessions.inc.php';
}

// Unset globally registered vars. this should protect us against any remote file inclusion attack
function _unset_vars(&$v) {
	$temp=array_keys($v);
	for ($i=0;isset($temp[$i]);++$i) {
		if ($temp[$i]!='v' && $temp[$i]!='temp') {
			unset($GLOBALS[$temp[$i]]);
		}
	}
}
if (ini_get('register_globals')=='1' || strtolower(ini_get('register_globals'))=='on') {
	$temp=array('_GET','_POST','_SERVER','_COOKIE','_ENV','_SESSION','_REQUEST','_FILES','_SESSION');
	foreach ($temp as $v) {
		if (isset(${'HTTP'.$v.'_VARS'}) && is_array(${'HTTP'.$v.'_VARS'})) {
			_unset_vars(${'HTTP'.$v.'_VARS'});
			unset(${'HTTP'.$v.'_VARS'});
		}
		if (isset(${$v}) && is_array(${$v})) {
			_unset_vars(${$v});
		}
	}
	if (isset($HTTP_POST_FILES) && is_array($HTTP_POST_FILES)) {
		_unset_vars($HTTP_POST_FILES);
		@reset($HTTP_POST_FILES);
	}
}

define('FIELD_LOCATION',107);
$__field2type[FIELD_LOCATION]=TYPE_INT;
$__field2format[FIELD_LOCATION]=0;
define('FIELD_RANGE',108);
require _BASEPATH_.'/includes/classes/phemplate.class.php';
if (is_dir(dirname(__FILE__).'/../install')) {
	die('Please remove the install folder.');
}

// often used vars in skins
$tplvars['sitename']=_SITENAME_;
$tplvars['baseurl']=_BASEURL_;
$tplvars['photourl']=_PHOTOURL_;
if (isset($_SERVER['REQUEST_URI'])) {
	$relative_path=@str_repeat('../',substr_count(preg_replace('~//+~','/',$_SERVER['SCRIPT_NAME']),'/')-(substr_count(_BASEURL_,'/')-2)-1);
	$tplvars['relative_url']=@str_repeat('../',substr_count(preg_replace('~//+~','/',$_SERVER['REQUEST_URI']),'/')-(substr_count(_BASEURL_,'/')-2)-1);
}

$accepted_currencies=array('USD'=>'USD','EUR'=>'EUR','GBP'=>'GBP');

$tplvars['js_lib_v']=5;	// change this when you update any .js file. This should force a reload of the js scripts.
$tplvars['remote_site']='http://www.datemill.com';
$_cache_config=array('cacheDir'=>_BASEPATH_.'/cache2/','lifeTime'=>null,'fileLocking'=>false,'writeControl'=>false,'readControl'=>false,'hashedDirectoryLevel'=>3);

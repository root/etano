<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/my_searches_rename.php
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
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/my_searches.inc.php';
check_login_member('save_searches');

if (is_file(_BASEPATH_.'/events/processors/my_searches_rename.php')) {
	include_once _BASEPATH_.'/events/processors/my_searches_rename.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_searches.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['search_id']=sanitize_and_format_gpc($_POST,'search_id',TYPE_INT,0,0);
	$input['title']=sanitize_and_format_gpc($_POST,'title',TYPE_STRING,FIELD_TEXTFIELD,'');

	if (empty($input['title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][78];
	}

	if (!$error) {
		$query="UPDATE `{$dbtable_prefix}user_searches` SET `title`='".$input['title']."' WHERE `search_id`=".$input['search_id']." AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (isset($_on_before_update)) {
			for ($i=0;isset($_on_before_update[$i]);++$i) {
				call_user_func($_on_before_update[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=$GLOBALS['_lang'][79];
		if (isset($_on_after_update)) {
			for ($i=0;isset($_on_after_update[$i]);++$i) {
				call_user_func($_on_after_update[$i]);
			}
		}
	} else {
		$nextpage='my_searches.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				call_user_func($_on_error[$i]);
			}
		}
	}
}

if (!isset($_POST['silent'])) {
	redirect2page($nextpage,$topass,$qs);
} else {
	echo $topass['message']['text'];
	die;
}

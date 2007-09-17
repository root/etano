<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/my_responses_delete.php
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
check_login_member('saved_messages');

if (is_file(_BASEPATH_.'/events/processors/my_responses_delete.php')) {
	include_once _BASEPATH_.'/events/processors/my_responses_delete.php';
}

$topass=array();
$mtpl_id=isset($_GET['mtpl_id']) ? (int)$_GET['mtpl_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}user_mtpls` WHERE `mtpl_id`=$mtpl_id AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (isset($_on_before_delete)) {
	for ($i=0;isset($_on_before_delete[$i]);++$i) {
		call_user_func($_on_before_delete[$i]);
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Response deleted.';     // translate

if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
} else {
	$nextpage='my_responses.php';
}
if (isset($_on_after_delete)) {
	for ($i=0;isset($_on_after_delete[$i]);++$i) {
		call_user_func($_on_after_delete[$i]);
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

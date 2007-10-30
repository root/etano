<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/folders_delete.php
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
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('manage_folders');

if (is_file(_BASEPATH_.'/events/processors/folders_delete.php')) {
	include_once _BASEPATH_.'/events/processors/folders_delete.php';
}

$qs='';
$qs_sep='';
$topass=array();
$folder_id=isset($_GET['fid']) ? (int)$_GET['fid'] : 0;

$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`=".FOLDER_INBOX.", `del`=1 WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_folder_id`=$folder_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_folder_id`=$folder_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}user_folders` WHERE `folder_id`=$folder_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
if (isset($_on_before_delete)) {
	for ($i=0;isset($_on_before_delete[$i]);++$i) {
		call_user_func($_on_before_delete[$i]);
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="UPDATE `{$dbtable_prefix}user_inbox` SET `del`=1 WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_folder_id`=$folder_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']=$GLOBALS['_lang'][55];

if (isset($_on_after_delete)) {
	for ($i=0;isset($_on_after_delete[$i]);++$i) {
		call_user_func($_on_after_delete[$i]);
	}
}
redirect2page('folders.php',$topass,$qs);

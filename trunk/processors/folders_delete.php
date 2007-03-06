<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/folders_delete.php
$Revision$
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
check_login_member(14);

$qs='';
$qs_sep='';
$topass=array();
$folder_id=isset($_GET['fid']) ? (int)$_GET['fid'] : 0;

$query="UPDATE `{$dbtable_prefix}user_inbox` SET `fk_folder_id`='".FOLDER_INBOX."', `del`=1 WHERE `fk_folder_id`='$folder_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE `fk_folder_id`='$folder_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}user_folders` WHERE `folder_id`='$folder_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="UPDATE `{$dbtable_prefix}user_inbox` SET `del`=1 WHERE `fk_folder_id`='$folder_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Folder deleted.';     // translate
redirect2page('folders.php',$topass,$qs);
?>
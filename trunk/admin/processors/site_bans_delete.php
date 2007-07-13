<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_bans_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/logs.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$ban_id=isset($_GET['ban_id']) ? (int)$_GET['ban_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}site_bans` WHERE `ban_id`='$ban_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
regenerate_langstrings_array();
regenerate_ban_array();

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Limit removed successfully.';

redirect2page('admin/site_bans.php',$topass,$qs);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/admin_accounts_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$admin_id=isset($_GET['admin_id']) ? (int)$_GET['admin_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}admin_accounts` WHERE `admin_id`='$admin_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Account removed successfully.';

redirect2page('admin/admin_accounts.php',$topass,$qs);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/subscriptions_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$subscr_id=isset($_GET['subscr_id']) ? (int)$_GET['subscr_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}subscriptions` WHERE `subscr_id`='$subscr_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Subscription deleted.';

redirect2page('admin/subscriptions.php',$topass,$qs);
?>
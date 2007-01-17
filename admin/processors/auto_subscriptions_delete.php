<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/auto_subscriptions_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$asubscr_id=isset($_GET['asubscr_id']) ? (int)$_GET['asubscr_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}auto_subscriptions` WHERE `asubscr_id`='$asubscr_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Subscription assignment deleted.';

redirect2page('admin/auto_subscriptions.php',$topass,$qs);
?>
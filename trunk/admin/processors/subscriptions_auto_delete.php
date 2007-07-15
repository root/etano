<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/subscriptions_auto_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$asubscr_id=isset($_GET['asubscr_id']) ? (int)$_GET['asubscr_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}subscriptions_auto` WHERE `asubscr_id`='$asubscr_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Subscription assignment deleted.';

redirect2page('admin/subscriptions_auto.php',$topass,$qs);

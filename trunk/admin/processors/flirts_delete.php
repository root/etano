<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/flirts_delete.php
$Revision: 21 $
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
$flirt_id=isset($_GET['flirt_id']) ? (int)$_GET['flirt_id'] : 0;

$query="DELETE FROM `{$dbtable_prefix}flirts` WHERE `flirt_id`='$flirt_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Flirt deleted.';

if (isset($_GET['o'])) {
	$qs.=$qs_sep.'o='.$_GET['o'];
	$qs_sep='&';
}
if (isset($_GET['r'])) {
	$qs.=$qs_sep.'r='.$_GET['r'];
	$qs_sep='&';
}
redirect2page('admin/flirts.php',$topass,$qs);
?>
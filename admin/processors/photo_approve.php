<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/photo_approve.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if (isset($_GET['photo_id']) && !empty($_GET['photo_id'])) {
	$input['photo_id']=(int)$_GET['photo_id'];

	$query="UPDATE `{$dbtable_prefix}user_photos` SET `status`='".PSTAT_APPROVED."',`last_changed`=now() WHERE `photo_id`='".$input['photo_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Photo approved. It will appear on site as soon as the cache for it is generated';
}

if (isset($_GET['o'])) {
	$qs.=$qs_sep.'o='.$_GET['o'];
	$qs_sep='&';
}
if (isset($_GET['r'])) {
	$qs.=$qs_sep.'r='.$_GET['r'];
	$qs_sep='&';
}
if (isset($_GET['search'])) {
	$qs.=$qs_sep.'search='.$_GET['search'];
	$qs_sep='&';
	$qs.=$qs_sep.'refresh=1';
}
redirect2page('admin/photo_search.php',$topass,$qs);
?>
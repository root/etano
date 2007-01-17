<?php
/******************************************************************************
newdsb
===============================================================================
File:                       popup_photo_view.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$main=sanitize_and_format_gpc($_GET,'main',TYPE_INT,0,0);
$photo_id=sanitize_and_format_gpc($_GET,'photo_id',TYPE_INT,0,0);
$user_id=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);

$photo=array();
$query='';
if (!empty($photo_id)) {
	$query="SELECT `photo_id`,`photo`,`fk_user_id`,`_user` as `user` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id`='$photo_id'";
} elseif (!empty($main) && !empty($user_id)) {
	$query="SELECT `photo_id`,`photo`,`fk_user_id`,`_user` as `user` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`='$user_id' AND `is_main`=1";
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$photo=mysql_fetch_assoc($res);
	$photo['user']=sanitize_and_format($photo['user'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
}

$tpl->set_file('content','popup_photo_view.html');
$tpl->set_var('photo',$photo);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','content',TPL_FINISH);
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/user_login.php
$Revision: 21 $
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
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$output['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
$tpl=new phemplate('skin/','remove_nonjs');

if (isset($_GET['top'])) {
	$content_file='user_login_top.html';
	$tpl->set_var('user',$_SESSION['user']);
} else {
	$content_file='user_login.html';
	$output['return']=rawurlencode($output['return']);
	$query="SELECT a.`user_id`,a.`user`,a.`status`,a.`membership` FROM `{$dbtable_prefix}user_accounts` a WHERE a.`user_id`='".$output['uid']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user=mysql_fetch_assoc($res);
//		$user['prefs']=get_user_settings($user['user_id'],1);
		$_SESSION['user']=$user;
	}
}

$tpl->set_file('content',$content_file);
$tpl->set_var('output',$output);
echo $tpl->process('','content');
?>
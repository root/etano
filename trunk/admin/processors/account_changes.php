<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/account_changes.php
$Revision: 67 $
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
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['act']=sanitize_and_format_gpc($_POST,'act',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['uid']=sanitize_and_format_gpc($_POST,'uid',TYPE_INT,0,0);
	$input['search']=sanitize_and_format_gpc($_POST,'search',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['o']=sanitize_and_format_gpc($_POST,'o',TYPE_INT,0,0);
	$input['r']=sanitize_and_format_gpc($_POST,'r',TYPE_INT,0,0);

	switch ($input['act']) {

		case 'status':
			$input['status']=sanitize_and_format_gpc($_POST,'status',TYPE_INT,0,0);
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `status`='".$input['status']."' WHERE `user_id`='".$input['uid']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Account status changed succesfully';
			break;

		case 'skin':
			$input['skin']=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `skin`='".$input['skin']."' WHERE `user_id`='".$input['uid']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Skin changed';
			break;

		case 'pass':
			$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `pass`=md5('".$input['pass']."') WHERE `user_id`='".$input['uid']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Password changed succesfully';
			break;

	}
	$qs.=$qs_sep.'uid='.$input['uid'];
	$qs_sep='&amp;';
	$qs.=$qs_sep.'search='.$input['search'];
	$qs.=$qs_sep.'o='.$input['o'];
	$qs.=$qs_sep.'r='.$input['r'];
}
if (!isset($_POST['silent'])) {
	redirect2page('admin/profile.php',$topass,$qs);
} else {
echo $topass['message']['text'];
}
?>
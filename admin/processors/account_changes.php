<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/account_changes.php
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (isset($_POST['search']) && !empty($_POST['search'])) {
	$input['search']=sanitize_and_format($_POST['search'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$input['search']."' AND `search_type`='"._SEARCH_USER_."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$input['uids']=explode(',',$results);
	}
} elseif (isset($_REQUEST['uids']) && !empty($_REQUEST['uids'])) {
	$input['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
}
$input['act']=sanitize_and_format_gpc($_POST,'act',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
$input['return']=rawurldecode(sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],''));

if (!empty($input['uids'])) {
	switch ($input['act']) {

		case 'status':
			$input['status']=sanitize_and_format_gpc($_POST,'status',TYPE_INT,0,0);
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `status`='".$input['status']."' WHERE `user_id` IN ('".join("','",$input['uids'])."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Account status changed succesfully';
			break;

		case 'skin':
			$input['skin']=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `skin`='".$input['skin']."' WHERE `user_id` IN ('".join("','",$input['uids'])."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Skin changed';
			break;

		case 'pass':
			$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
			$query="UPDATE `{$dbtable_prefix}user_accounts` SET `pass`=md5('".$input['pass']."') WHERE `user_id`='".$input['uids'][0]."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Password changed succesfully';
			break;

	}
}
if (!isset($_POST['silent'])) {
	$nextpage=_BASEURL_.'/admin/member_search.php';
	if (isset($input['return']) && !empty($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	}
	redirect2page($nextpage,$topass,$qs,true);
} else {
// for ajax password change.
	echo $topass['message']['text'];
}
?>
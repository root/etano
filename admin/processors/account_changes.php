<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/account_changes.php
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (!empty($_POST['search'])) {
	$input['search']=sanitize_and_format($_POST['search'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$input['search']."' AND `search_type`='".SEARCH_USER."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$input['uids']=explode(',',$results);
	}
} elseif (!empty($_REQUEST['uids'])) {
	$input['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
}
$input['act']=sanitize_and_format_gpc($_POST,'act',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

if (!empty($input['uids'])) {
	switch ($input['act']) {

		case 'status':
			$input['status']=sanitize_and_format_gpc($_POST,'status',TYPE_INT,0,0);
			$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `status`='".$input['status']."' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$input['uids'])."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Account status changed succesfully';
			break;

		case 'skin':
			$input['skin']=sanitize_and_format_gpc($_POST,'skin',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `skin`='".$input['skin']."' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$input['uids'])."')";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Skin changed';
			break;

		case 'pass':
			$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `".USER_ACCOUNT_PASS."`=md5('".$input['pass']."') WHERE `".USER_ACCOUNT_ID."`='".$input['uids'][0]."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Password changed succesfully';
			break;

	}
}
if (!isset($_POST['silent'])) {
	if (!empty($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	} else {
		$nextpage=_BASEURL_.'/admin/member_search.php';
	}
	redirect2page($nextpage,$topass,'',true);
} else {
// for ajax password change.
	echo $topass['message']['text'];
}
?>
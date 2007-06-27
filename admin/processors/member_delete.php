<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/member_delete.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (!empty($_REQUEST['search'])) {
	$input['search']=sanitize_and_format_gpc($_REQUEST,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$input['search']."' AND `search_type`='".SEARCH_USER."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$input['uids']=explode(',',$results);
	}
} elseif (!empty($_REQUEST['uids'])) {
	$input['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
}

$act=isset($_REQUEST['act']) ? (int)$_REQUEST['act'] : 1;
$input['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

if (!empty($input['uids'])) {
	if ($act==1) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `del`=1 WHERE `fk_user_id` IN ('".join("','",$input['uids'])."')";
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Member(s) marked for deletion.';
	} elseif ($act==-1) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `del`=0 WHERE `fk_user_id` IN ('".join("','",$input['uids'])."')";
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Member(s) undeleted.';
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}

$nextpage=_BASEURL_.'/admin/member_search.php';
if (!empty($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
}
redirect2page($nextpage,$topass,$qs,true);
?>
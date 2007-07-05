<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/email_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$query_strlen=10000;
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['uids']=isset($_POST['uids']) ? $_POST['uids'] : '';
	$input['uids']=explode('|',$input['uids']);
	$input['uids']=sanitize_and_format($input['uids'],TYPE_INT,0,array());
	$input['subject']=sanitize_and_format_gpc($_POST,'subject',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['message_body']=sanitize_and_format_gpc($_POST,'message_body',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	if (empty($input['uids'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No recipients selected';
	}
	if (empty($input['subject'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the subject of the message';
	}
	if (empty($input['message_body'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the message';
	}

	if (!$error) {
		$query="SELECT `email` FROM ".USER_ACCOUNTS_TABLE." WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$input['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$insert="INSERT INTO `{$dbtable_prefix}queue_email` (`to`,`subject`,`message_body`) VALUES ";
		$query=$insert;
		for ($i=0;$i<mysql_num_rows($res);++$i) {
			if (strlen($query)>$query_strlen) {
				$query=substr($query,0,-1);
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query=$insert;
			}
			$query.="('".mysql_result($res,$i,0)."','".$input['subject']."','".$input['message_body']."'),";
		}
		if ($query!=$insert) {
			$query=substr($query,0,-1);
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('Email queued for delivery to %s members',count($input['uids']));
	} else {
		$nextpage=_BASEURL_.'/admin/email_send.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['message_body']=addslashes_mq($_POST['message_body']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (empty($nextpage)) {
	$nextpage=_BASEURL_.'/admin/member_search.php';
	if (isset($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	}
}
redirect2page($nextpage,$topass,$qs,true);
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/message_send.php
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
	$input['subject']=sanitize_and_format_gpc($_POST,'subject',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$input['message_body']=sanitize_and_format_gpc($_POST,'message_body',TYPE_STRING,$__html2format[_HTML_TEXTAREA_],'');
	$input['return']=rawurldecode(sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],''));

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
		$insert="INSERT INTO `{$dbtable_prefix}user_inbox` (`fk_user_id`,`subject`,`message_body`,`date_sent`,`message_type`) VALUES ";
		$query=$insert;
		for ($i=0;isset($input['uids'][$i]);++$i) {
			if (strlen($query)>$query_strlen) {
				$query=substr($query,0,-1);
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query=$insert;
			}
			$query.="('".$input['uids'][$i]."','".$input['subject']."','".$input['message_body']."',now(),"._MESS_SYSTEM_."),";
		}
		if ($query!=$insert) {
			$query=substr($query,0,-1);
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('Message sent to %s members',count($input['uids']));
	} else {
		$nextpage=_BASEURL_.'/admin/message_send.php';
		// replace '\r' and '\n' strings with <enter> in all textareas
		$input['message_body']=preg_replace(array('/([^\\\])\\\n/','/([^\\\])\\\r/'),array("$1\n","$1"),$input['message_body']);
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
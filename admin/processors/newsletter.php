<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/newsletter.php
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
allow_dept(DEPT_ADMIN);

$query_strlen=10000;
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['subject']=sanitize_and_format_gpc($_POST,'subject',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['message_body']=sanitize_and_format_gpc($_POST,'message_body',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

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
		$config=get_site_option(array('allow_news'),'def_user_prefs');
		if ($config['allow_news']) {
			$query="SELECT `email` FROM ".USER_ACCOUNTS_TABLE." a LEFT JOIN `{$dbtable_prefix}user_settings2` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` AND b.`config_option`='allow_news' WHERE b.`fk_user_id` IS NULL OR b.`config_value`=1";
		} else {
			$query="SELECT `email` FROM ".USER_ACCOUNTS_TABLE." a,`{$dbtable_prefix}user_settings2` b WHERE a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` AND b.`config_option`='allow_news' AND b.`config_value`=1";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$insert="INSERT INTO `{$dbtable_prefix}queue_email` (`to`,`subject`,`message_body`) VALUES ";
		$query=$insert;
		$i=0;	// keep this!
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
		$topass['message']['text']=sprintf('Newsletter queued for delivery to %s members',$i-1);
	} else {
		$nextpage=_BASEURL_.'/admin/newsletter.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['message_body']=addslashes_mq($_POST['message_body']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (empty($nextpage)) {
	if (isset($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	} else {
		$nextpage=_BASEURL_.'/admin/cpanel.php';
	}
}
redirect2page($nextpage,$topass,'',true);
?>
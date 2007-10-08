<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/email_change.php
$Revision: 306 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_settings.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['email']=sanitize_and_format_gpc($_POST,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['email2']=sanitize_and_format_gpc($_POST,'email2',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	if ($input['email']!=$input['email2']) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]="The emails do not match.";
		$input['error_email']='red_border';
	}
	if (empty($input['email'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]="Please enter the new email address you wish to use.";
		$input['error_email']='red_border';
	}

	if (!$error) {
		$query="REPLACE INTO `{$dbtable_prefix}user_settings2` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`config_option`='new_email',`config_value`='".$input['email']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$input['email2']=rawurlencode($input['email']);
		send_template_email($input['email'],sprintf('%s email change confirmation',_SITENAME_),'email_change_confirm.html',get_my_skin(),$input);
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text'][]="An email has been sent to the email address you specified.";
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);

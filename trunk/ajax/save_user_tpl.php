<?php
/******************************************************************************
newdsb
===============================================================================
File:                       ajax/save_user_tpl.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/sessions.inc.php';
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';
require_once dirname(__FILE__).'/../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	if (isset($_SESSION['user']['user_id']) && allow_at_level(15,$_SESSION['user']['membership'])) {
		if (isset($_POST['subject']) && !empty($_POST['subject']) && isset($_POST['message_body']) && !empty($_POST['message_body'])) {
			$subject=sanitize_and_format($_POST['subject'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
			$message_body=sanitize_and_format($_POST['message_body'],TYPE_STRING,$__html2format[_HTML_TEXTAREA_]);
			$query="INSERT INTO `{$dbtable_prefix}user_mtpls` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`subject`='$subject',`message_body`='$message_body'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$output=1;
		}
	} else {
		$output=2;
	}
}
echo $output;
?>
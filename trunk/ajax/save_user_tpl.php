<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/save_user_tpl.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';

$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && allow_at_level('saved_messages',$_SESSION[_LICENSE_KEY_]['user']['membership'])) {
		if (!empty($_POST['subject']) && !empty($_POST['message_body'])) {
			$subject=sanitize_and_format($_POST['subject'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE | FORMAT_HTML2TEXT_FULL);
			$message_body=sanitize_and_format($_POST['message_body'],TYPE_STRING,$__field2format[FIELD_TEXTAREA] | FORMAT_RUDECODE | FORMAT_HTML2TEXT_FULL);
			$query="INSERT INTO `{$dbtable_prefix}user_mtpls` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`subject`='$subject',`message_body`='$message_body'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$output=1;
		}
	} else {
		$output=2;
	}
}
echo $output;

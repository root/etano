<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/user_sentmail_delete.php
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

$qs='';
$qs_sep='';
$topass=array();
$uid=isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
$return=rawurldecode(sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],''));

$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id_other`='$uid'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$numrows=mysql_affected_rows();

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']=sprintf('%s messages sent by this member were deleted',$numrows);

$nextpage=_BASEURL_.'/admin/member_search.php';
if (isset($return) && !empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
}
redirect2page($nextpage,$topass,$qs,true);
?>
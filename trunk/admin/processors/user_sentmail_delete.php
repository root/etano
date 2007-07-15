<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/user_sentmail_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$uid=isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

$query="DELETE FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id_other`='$uid'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$numrows=mysql_affected_rows();

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']=sprintf('%s messages sent by this member were deleted',$numrows);

$nextpage=_BASEURL_.'/admin/member_search.php';
if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
}
redirect2page($nextpage,$topass,$qs,true);

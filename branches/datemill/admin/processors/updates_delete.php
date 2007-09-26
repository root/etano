<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/updates_delete.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/classes/fileop.class.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$update_id=isset($_GET['update_id']) ? (int)$_GET['update_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `filename` FROM `updates` WHERE `update_id`=$update_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$filename=mysql_result($res,0,0);
	$query="DELETE FROM `update_requirements` WHERE `fk_update_id`=$update_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

	if (is_file(_BASEPATH_.'/dafilez/updates/'.$filename)) {
		$fileop=new fileop();
		$fileop->delete(_BASEPATH_.'/dafilez/updates/'.$filename);
	}

	$query="DELETE FROM `updates` WHERE `update_id`=$update_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Update deleted.';
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Update not found.';
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/updates.php';
}
redirect2page($nextpage,$topass,'',true);

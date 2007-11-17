<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/error_log_delete.php
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
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$log_id=isset($_GET['log_id']) ? (int)$_GET['log_id'] : 0;
$act=isset($_GET['act']) ? $_GET['act'] : '';

if ($act=='all') {
	$query="TRUNCATE TABLE `{$dbtable_prefix}error_log`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
} elseif ($act=='one') {
	$query="DELETE FROM `{$dbtable_prefix}error_log` WHERE `log_id`=$log_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Ok';

redirect2page('admin/error_log.php',$topass,$qs);

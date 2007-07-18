<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/filters_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
check_login_member('manage_folders');

if (is_file(_BASEPATH_.'/events/processors/filters_delete.php')) {
	include_once _BASEPATH_.'/events/processors/filters_delete.php';
}

$qs='';
$qs_sep='';
$topass=array();
$nextpage='filters.php';
$filter_id=isset($_GET['filter_id']) ? (int)$_GET['filter_id'] : 0;
$where=isset($_GET['uid']) ? "`filter_type`="._FILTER_USER_." AND `field_value`='".(int)$_GET['uid']."' AND `fk_folder_id`=".FOLDER_SPAMBOX : "`filter_id`=".$filter_id;

$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE $where AND `fk_user_id`=".$_SESSION['user']['user_id'];
if (isset($_on_before_delete)) {
	for ($i=0;isset($_on_before_delete[$i]);++$i) {
		eval($_on_before_delete[$i].'();');
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Filter deleted.';     // translate

if (isset($_GET['uid'])) {
	$nextpage='message_read.php';
}
if (isset($_GET['mail_id'])) {
	$qs.=$qs_sep.'mail_id='.$_GET['mail_id'];
	$qs_sep='&';
}
if (isset($_GET['fid'])) {
	$qs.=$qs_sep.'fid='.$_GET['fid'];
	$qs_sep='&';
}
if (isset($_GET['o'])) {
	$qs.=$qs_sep.'o='.$_GET['o'];
	$qs_sep='&';
}
if (isset($_GET['r'])) {
	$qs.=$qs_sep.'r='.$_GET['r'];
	$qs_sep='&';
}
if (isset($_GET['ob'])) {
	$qs.=$qs_sep.'ob='.$_GET['ob'];
	$qs_sep='&';
}
if (isset($_GET['od'])) {
	$qs.=$qs_sep.'od='.$_GET['od'];
	$qs_sep='&';
}

if (isset($_on_after_delete)) {
	for ($i=0;isset($_on_after_delete[$i]);++$i) {
		eval($_on_after_delete[$i].'();');
	}
}
redirect2page($nextpage,$topass,$qs);

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/filters.php
$Revision: 0 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$qs='';
$qs_sep='';
$topass=array();
$nextpage='filters.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$filter_id=sanitize_and_format_gpc($_POST,'filter_id',TYPE_INT,0,0);
	$num_filters=0;
	if (!empty($filter_id)) {
		$num_filters=1;
	}
	if ($_POST['act']=='del') {
		if (is_array($filter_id)) {
			$num_filters=count($filter_id);
			$filter_id=join("','",array_keys($filter_id));
		}
		$query="DELETE FROM `{$dbtable_prefix}message_filters` WHERE `filter_id` IN ('$filter_id') AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1s filter(s) deleted.',$num_filters);     // translate
	}
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
		$qs_sep='&';
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
		$qs_sep='&';
	}
	if (isset($_POST['ob'])) {
		$qs.=$qs_sep.'ob='.$_POST['ob'];
		$qs_sep='&';
	}
	if (isset($_POST['od'])) {
		$qs.=$qs_sep.'od='.$_POST['od'];
		$qs_sep='&';
	}
}
redirect2page($nextpage,$topass,$qs);
?>
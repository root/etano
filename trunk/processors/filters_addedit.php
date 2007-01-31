<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/filters_addedit.php
$Revision: 21 $
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
require_once '../includes/tables/message_filters.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(11);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='filters.php';
$input['field']='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($message_filters_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$message_filters_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];
	$input['rule_value']=$_POST['rule_value'];
	
	switch ($input['filter_type']) {
	
	case _FILTER_USER_:
		if ($input['field_value']=get_userid_by_user($input['rule_value'])){
			$input['field']='fk_user_id';
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='User \''.$input['rule_value'].'\' doesn\'t exist. Filter not saved.';     // translate
		}
		break;
	
	default:
		break;
		
	}

	if (!$error) {
		if (!empty($input['filter_id'])) {
			$query="UPDATE IGNORE `{$dbtable_prefix}message_filters` SET ";
			$i=0;
			foreach ($message_filters_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					if ($i==0) {
						$query.="`$k`='".$input[$k]."'";
					} else {
						$query.=",`$k`='".$input[$k]."'";
					}
				}
				++$i;
			}
			$query.=" WHERE `filter_id`='".$input['filter_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($affected_rows)) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Filter changed successfully.';     // translate
			} else {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Filter not changed. A similar filter already exists.';     // translate
			}
		} else {
			$query="INSERT IGNORE INTO `{$dbtable_prefix}message_filters` SET ";
			$i=0;
			foreach ($message_filters_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					if ($i==0) {
						$query.="`$k`='".$input[$k]."'";
					} else {
						$query.=",`$k`='".$input[$k]."'";
					}
				}
				++$i;
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$affected_rows=mysql_affected_rows();
			if (!empty($affected_rows)) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Filter added.';     // translate
			} else {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Filter not added. A similar filter already exists.';     // translate
			}
		}
	} else {
		$nextpage='filters_addedit.php';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
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
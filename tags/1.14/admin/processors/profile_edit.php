<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/field_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
$nextpage='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['fk_user_id']=sanitize_and_format_gpc($_POST,'fk_user_id',TYPE_INT,0,0);
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	$on_changes=array();
	$ch=0;
	$texts=array();
	foreach ($_pfields as $field_id=>$field) {
		$_pfields[$field_id]->set_value($_POST,true);
		// check for input errors
		if (true!==($temp=$_pfields[$field_id]->validation_server())) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			if (empty($temp['text'])) {
				$topass['message']['text']=$GLOBALS['_lang'][69];
			} else {
				$topass['message']['text']=$temp['text'];
			}
			$input['error_'.$_pfields[$field_id]->config['dbfield']]='red_border';
		}
		if (!$error) {
			if (!empty($_pfields[$field_id]->config['fn_on_change'])) {
				$on_changes[]=array('fn'=>$_pfields[$field_id]->config['fn_on_change'],
									'param2'=>$_pfields[$field_id]->get_value(),
									'param3'=>$_pfields[$field_id]->config['dbfield']);
			}
		}
	}

	if (!$error) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='".gmdate('YmdHis')."'";
		foreach ($_pfields as $field_id=>$field) {
			$query.=','.$field->query_set();
		}
		$query.=" WHERE `fk_user_id`=".$input['fk_user_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_affected_rows()) {
			$query="INSERT IGNORE INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$input['fk_user_id']."',`last_changed`='".gmdate('YmdHis')."',`status`=".STAT_APPROVED;
			foreach ($_pfields as $field_id=>$field) {
				$query.=','.$field->query_set();
			}
		}

		for ($i=0;isset($on_changes[$i]);++$i) {
			if (function_exists($on_changes[$i]['fn'])) {
				call_user_func($on_changes[$i]['fn'],$input['fk_user_id'],$on_changes[$i]['param2'],$on_changes[$i]['param3']);
			}
		}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Member profile has been changed.';
	} else {
		$nextpage=_BASEURL_.'/admin/profile_edit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		for ($i=0;isset($texts[$i]);++$i) {
			$input[$texts[$i]]=addslashes_mq($_POST[$texts[$i]]);
		}
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (empty($nextpage)) {
	$nextpage=_BASEURL_.'/admin/member_search.php';
	if (!empty($input['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$input['return'];
	}
}
redirect2page($nextpage,$topass,$qs,true);

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
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
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
	$input['uid']=sanitize_and_format_gpc($_POST,'uid',TYPE_INT,0,0);
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	$on_changes=array();
	$ch=0;
	$texts=array();
	foreach ($_pfields as $field_id=>$field) {
		if ($field['editable']) {
			switch ($field['field_type']) {

				case FIELD_DATE:
					$input[$field['dbfield'].'_month']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_month',TYPE_INT,0,0);
					$input[$field['dbfield'].'_day']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_day',TYPE_INT,0,0);
					$input[$field['dbfield'].'_year']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_year',TYPE_INT,0,0);
					if (!empty($input[$field['dbfield'].'_year']) && !empty($input[$field['dbfield'].'_month']) && !empty($input[$field['dbfield'].'_day'])) {
						$input[$field['dbfield']]=$input[$field['dbfield'].'_year'].'-'.str_pad($input[$field['dbfield'].'_month'],2,'0',STR_PAD_LEFT).'-'.str_pad($input[$field['dbfield'].'_day'],2,'0',STR_PAD_LEFT);
					}
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=array('year'=>$input[$field['dbfield'].'_year'],'month'=>$input[$field['dbfield'].'_month'],'day'=>$input[$field['dbfield'].'_day']);
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}
					break;

				case FIELD_LOCATION:
					$input[$field['dbfield'].'_country']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_country',TYPE_INT,0,0);
					$input[$field['dbfield'].'_state']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_state',TYPE_INT,0,0);
					$input[$field['dbfield'].'_city']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_city',TYPE_INT,0,0);
					$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_zip',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=array('country'=>$input[$field['dbfield'].'_country'],'state'=>$input[$field['dbfield'].'_state'],'city'=>$input[$field['dbfield'].'_city'],'zip'=>$input[$field['dbfield'].'_zip']);
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}
					break;

				default:
					$input[$field['dbfield']]=sanitize_and_format_gpc($_POST,$field['dbfield'],$__field2type[$field['field_type']],$__field2format[$field['field_type']],'');
					if ($field['field_type']==FIELD_TEXTAREA) {
						$texts[]=$field['dbfield'];
					}
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=$input[$field['dbfield']];
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}

			}
			// check for input errors
			if (isset($field['required']) && ((empty($input[$field['dbfield']]) && $field['field_type']!=FIELD_LOCATION) || ($field['field_type']==FIELD_LOCATION && empty($input[$field['dbfield'].'_country'])))) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='The fields outlined below are required and must not be empty';
				$input['error_'.$field['dbfield']]='red_border';
			}
		}
	}

	if (!$error) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='".gmdate('YmdHis')."',`status`=".STAT_APPROVED;
		foreach ($_pfields as $field_id=>$field) {
			if ($field['editable']) {
				if ($field['field_type']==FIELD_LOCATION) {
					$query.=",`".$field['dbfield']."_country`=".$input[$field['dbfield'].'_country'].",`".$field['dbfield']."_state`=".$input[$field['dbfield'].'_state'].",`".$field['dbfield']."_city`=".$input[$field['dbfield'].'_city'].",`".$field['dbfield']."_zip`='".$input[$field['dbfield'].'_zip']."'";
				} else {
					if (isset($input[$field['dbfield']])) {
						$query.=',`'.$field['dbfield']."`='".$input[$field['dbfield']]."'";
					}
				}
			}
		}
		$query.=" WHERE `fk_user_id`=".$input['uid'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_affected_rows()) {
			$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`last_changed`='".gmdate('YmdHis')."',`status`=".STAT_APPROVED;
			foreach ($_pfields as $field_id=>$field) {
				if ($field['editable']) {
					if ($field['field_type']==FIELD_LOCATION) {
						$query.=",`".$field['dbfield']."_country`=".$input[$field['dbfield'].'_country'].",`".$field['dbfield']."_state`=".$input[$field['dbfield'].'_state'].",`".$field['dbfield']."_city`=".$input[$field['dbfield'].'_city'].",`".$field['dbfield']."_zip`='".$input[$field['dbfield'].'_zip']."'";
					} else {
						if (isset($input[$field['dbfield']])) {
							$query.=',`'.$field['dbfield']."`='".$input[$field['dbfield']]."'";
						}
					}
				}
			}
		}

		for ($i=0;isset($on_changes[$i]);++$i) {
			if (function_exists($on_changes[$i]['fn'])) {
				call_user_func($on_changes[$i]['fn'],$input['uid'],$on_changes[$i]['param2'],$on_changes[$i]['param3']);
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

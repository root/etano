<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/profile_edit.php
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
require_once '../includes/field_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(1);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='profile.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$pcat_id=sanitize_and_format_gpc($_POST,'pcat_id',TYPE_INT,0,0);
	if (isset($_pcats[$pcat_id]) && count($_pcats[$pcat_id]['fields'])>0) {
		$config=get_site_option(array('manual_profile_approval'),'core');
		$on_changes=array();
		$ch=0;
		$texts=array();
		foreach ($_pcats[$pcat_id]['fields'] as $field_id) {
			$field=$_pfields[$field_id];
			switch ($field['html_type']) {

				case _HTML_TEXTFIELD_:
				case _HTML_TEXTAREA_:
					$input[$field['dbfield']]=sanitize_and_format_gpc($_POST,$field['dbfield'],$__html2type[$field['html_type']],$__html2format[$field['html_type']],'');
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=$input[$field['dbfield']];
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}
					$texts[]=$field['dbfield'];		// need to know if any TA/TF were changed
					break;

				case _HTML_DATE_:
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

				case _HTML_LOCATION_:
					$input[$field['dbfield'].'_country']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_country',TYPE_INT,0,0);
					$input[$field['dbfield'].'_state']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_state',TYPE_INT,0,0);
					$input[$field['dbfield'].'_city']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_city',TYPE_INT,0,0);
					$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_POST,$field['dbfield'].'_zip',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=array('country'=>$input[$field['dbfield'].'_country'],'state'=>$input[$field['dbfield'].'_state'],'city'=>$input[$field['dbfield'].'_city'],'zip'=>$input[$field['dbfield'].'_zip']);
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}
					break;

				default:
					$input[$field['dbfield']]=sanitize_and_format_gpc($_POST,$field['dbfield'],$__html2type[$field['html_type']],$__html2format[$field['html_type']],'');
					if (isset($field['fn_on_change'])) {
						$on_changes[$ch]['fn']=$field['fn_on_change'];
						$on_changes[$ch]['param2']=$input[$field['dbfield']];
						$on_changes[$ch]['param3']=$field['dbfield'];
						++$ch;
					}

			}
			// check for input errors
			if (isset($field['required']) && ((empty($input[$field['dbfield']]) && $field['html_type']!=_HTML_LOCATION_) || ($field['html_type']==_HTML_LOCATION_ && empty($input[$field['dbfield'].'_country'])))) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']=$_lang[25];
				$input['error_'.$field['dbfield']]='red_border';
			}
		}

		if (!$error) {
			$query="SELECT `fk_user_id`";
			if (!empty($texts)) {
				$query.=',`'.join('`,`',$texts).'`';	// get the old value of the texts for comparing with new values
			}
			$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$is_update=false;
			$old_text_values=array();
			if (mysql_num_rows($res)) {
				$is_update=true;
				$old_text_values=mysql_fetch_assoc($res);
			}
			if ($is_update) {
				$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='".gmdate('YmdHis')."'";
				$force_pending=false;
				if (!empty($config['manual_profile_approval']) && !empty($texts)) {
					for ($i=0;isset($texts[$i]);++$i) {
						if (strcmp($old_text_values[$texts[$i]],$input[$texts[$i]])!=0) {
							$force_pending=true;		// if new!=old need to set profile status to pending.
							break;
						}
					}
				}
				if ($force_pending) {
					$query.=",`status`='".PSTAT_PENDING."'";
				} else {
					$query.=",`status`='".PSTAT_APPROVED."'";
				}
			} else {		// insert here
				$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`last_changed`='".gmdate('YmdHis')."'";
				if (!empty($config['manual_profile_approval'])) {
					$query.=",`status`='".PSTAT_PENDING."'";
				} else {
					$query.=",`status`='".PSTAT_APPROVED."'";
				}
			}
			foreach ($_pcats[$pcat_id]['fields'] as $v) {
				if ($_pfields[$v]['html_type']==_HTML_LOCATION_) {
					$query.=",`".$_pfields[$v]['dbfield']."_country`='".$input[$_pfields[$v]['dbfield'].'_country']."',`".$_pfields[$v]['dbfield']."_state`='".$input[$_pfields[$v]['dbfield'].'_state']."',`".$_pfields[$v]['dbfield']."_city`='".$input[$_pfields[$v]['dbfield'].'_city']."',`".$_pfields[$v]['dbfield']."_zip`='".$input[$_pfields[$v]['dbfield'].'_zip']."'";
				} else {
					if (isset($input[$_pfields[$v]['dbfield']])) {
						$query.=',`'.$_pfields[$v]['dbfield']."`='".$input[$_pfields[$v]['dbfield']]."'";
					}
				}
			}
			if ($is_update) {
				$query.=" WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			for ($i=0;isset($on_changes[$i]);++$i) {
				if (function_exists($on_changes[$i]['fn'])) {
					eval($on_changes[$i]['fn'].'($_SESSION[\'user\'][\'user_id\'],$on_changes[$i][\'param2\'],$on_changes[$i][\'param3\']);');
				}
			}

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Your profile has been changed.';
		} else {
			$nextpage='profile_edit.php';
// 		you must replace '\r' and '\n' strings with <enter> in all textareas like this:
//		$input['x']=preg_replace(array('/([^\\\])\\\n/','/([^\\\])\\\r/'),array("$1\n","$1"),$input['x']);
			$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
			$input['pcat_id']=$pcat_id;
			$topass['input']=$input;
		}
	}
}
redirect2page($nextpage,$topass,$qs);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/field_functions.inc.php';
check_login_member('auth');

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
		$config=get_site_option(array('manual_profile_approval','ta_len'),'core');
		$on_changes=array();
		$ch=0;
		$texts=array();
		foreach ($_pcats[$pcat_id]['fields'] as $field_id) {
			$field=$_pfields[$field_id];
			if ($field['editable']) {
				switch ($field['field_type']) {

					case FIELD_TEXTFIELD:
						$input[$field['dbfield']]=remove_banned_words(sanitize_and_format_gpc($_POST,$field['dbfield'],$__field2type[$field['field_type']],$__field2format[$field['field_type']],''));
						if (isset($field['fn_on_change'])) {
							$on_changes[$ch]['fn']=$field['fn_on_change'];
							$on_changes[$ch]['param2']=$input[$field['dbfield']];
							$on_changes[$ch]['param3']=$field['dbfield'];
							++$ch;
						}
						$texts[]=$field['dbfield'];		// need to know if any TA/TF were changed
						break;

					case FIELD_TEXTAREA:
						$input[$field['dbfield']]=sanitize_and_format_gpc($_POST,$field['dbfield'],$__field2type[$field['field_type']],$__field2format[$field['field_type']],'');
						if (!empty($config['ta_len'])) {
							$input[$field['dbfield']]=substr($input[$field['dbfield']],0,$config['ta_len']);
						}
						$input[$field['dbfield']]=remove_banned_words($input[$field['dbfield']]);
						if (isset($field['fn_on_change'])) {
							$on_changes[$ch]['fn']=$field['fn_on_change'];
							$on_changes[$ch]['param2']=$input[$field['dbfield']];
							$on_changes[$ch]['param3']=$field['dbfield'];
							++$ch;
						}
						$texts[]=$field['dbfield'];		// need to know if any TA/TF were changed
						break;

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
					$topass['message']['text']='The fields outlined below are required and must not be empty.';
					$input['error_'.$field['dbfield']]='red_border';
				}
			}	// if ($field['editable'])
		}	// foreach()

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
				$old_text_values=sanitize_and_format($old_text_values,TYPE_STRING,$__field2format[TEXT_DB2DB]);
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
					$query.=",`status`='".STAT_PENDING."'";
				} else {
					//keep it to whatever it was
					//$query.=",`status`='".STAT_APPROVED."'";
				}
			} else {		// insert here
				$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`last_changed`='".gmdate('YmdHis')."'";
				if (!empty($config['manual_profile_approval'])) {
					$query.=",`status`='".STAT_PENDING."'";
				} else {
					$query.=",`status`='".STAT_APPROVED."'";
				}
			}
			foreach ($_pcats[$pcat_id]['fields'] as $v) {
				if ($_pfields[$v]['field_type']==FIELD_LOCATION) {
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

// execute the on_change triggers of every field
			for ($i=0;isset($on_changes[$i]);++$i) {
				if (function_exists($on_changes[$i]['fn'])) {
					eval($on_changes[$i]['fn'].'($_SESSION[\'user\'][\'user_id\'],$on_changes[$i][\'param2\'],$on_changes[$i][\'param3\']);');
				}
			}

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Your profile has been changed.';
		} else {
			$nextpage='profile_edit.php';
//	 		you must re-read all textareas from $_POST like this:
//			$input['x']=addslashes_mq($_POST['x']);
			for ($i=0;isset($texts[$i]);++$i) {	// here we get textfields too but we don't bother...
				$input[$texts[$i]]=addslashes_mq($_POST[$texts[$i]]);
			}
			$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
			$input['pcat_id']=$pcat_id;
			$topass['input']=$input;
		}
	}
}
redirect2page($nextpage,$topass,$qs);
?>
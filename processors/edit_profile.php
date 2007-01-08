<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/edit_profile.php
$Revision: 85 $
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
//check_login_member(_FREELEVEL_);

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
		$on_changes=array();
		$ch=0;
		foreach ($_pcats[$pcat_id]['fields'] as $field_id) {
			switch ($_pfields[$field_id]['html_type']) {

				case _HTML_DATE_:
					$input[$_pfields[$field_id]['dbfield'].'_month']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_month',TYPE_INT,0,0);
					$input[$_pfields[$field_id]['dbfield'].'_day']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_day',TYPE_INT,0,0);
					$input[$_pfields[$field_id]['dbfield'].'_year']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_year',TYPE_INT,0,0);
					if (!empty($input[$_pfields[$field_id]['dbfield'].'_year']) && !empty($input[$_pfields[$field_id]['dbfield'].'_month']) && !empty($input[$_pfields[$field_id]['dbfield'].'_day'])) {
						$input[$_pfields[$field_id]['dbfield']]=$input[$_pfields[$field_id]['dbfield'].'_year'].'-'.str_pad($input[$_pfields[$field_id]['dbfield'].'_month'],2,'0',STR_PAD_LEFT).'-'.str_pad($input[$_pfields[$field_id]['dbfield'].'_day'],2,'0',STR_PAD_LEFT);
					}
					if (isset($_pfields[$field_id]['fn_on_change'])) {
						$on_changes[$ch]['fn']=$_pfields[$field_id]['fn_on_change'];
						$on_changes[$ch]['param2']=array('year'=>$input[$_pfields[$field_id]['dbfield'].'_year'],'month'=>$input[$_pfields[$field_id]['dbfield'].'_month'],'day'=>$input[$_pfields[$field_id]['dbfield'].'_day']);
						$on_changes[$ch]['param3']=$_pfields[$field_id]['dbfield'];
						++$ch;
					}
					break;

				case _HTML_LOCATION_:
					$input[$_pfields[$field_id]['dbfield'].'_country']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_country',TYPE_INT,0,0);
					$input[$_pfields[$field_id]['dbfield'].'_state']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_state',TYPE_INT,0,0);
					$input[$_pfields[$field_id]['dbfield'].'_city']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_city',TYPE_INT,0,0);
					$input[$_pfields[$field_id]['dbfield'].'_zip']=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'].'_zip',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
					if (isset($_pfields[$field_id]['fn_on_change'])) {
						$on_changes[$ch]['fn']=$_pfields[$field_id]['fn_on_change'];
						$on_changes[$ch]['param2']=array('country'=>$input[$_pfields[$field_id]['dbfield'].'_country'],'state'=>$input[$_pfields[$field_id]['dbfield'].'_state'],'city'=>$input[$_pfields[$field_id]['dbfield'].'_city'],'zip'=>$input[$_pfields[$field_id]['dbfield'].'_zip']);
						$on_changes[$ch]['param3']=$_pfields[$field_id]['dbfield'];
						++$ch;
					}
					break;

				default:
					$input[$_pfields[$field_id]['dbfield']]=sanitize_and_format_gpc($_POST,$_pfields[$field_id]['dbfield'],$__html2type[$_pfields[$field_id]['html_type']],$__html2format[$_pfields[$field_id]['html_type']],'');
					if (isset($_pfields[$field_id]['fn_on_change'])) {
						$on_changes[$ch]['fn']=$_pfields[$field_id]['fn_on_change'];
						$on_changes[$ch]['param2']=$input[$_pfields[$field_id]['dbfield']];
						$on_changes[$ch]['param3']=$_pfields[$field_id]['dbfield'];
						++$ch;
					}

			}
			// check for input errors
			if (isset($_pfields[$field_id]['required']) && ((empty($input[$_pfields[$field_id]['dbfield']]) && $_pfields[$field_id]['html_type']!=_HTML_LOCATION_) || ($_pfields[$field_id]['html_type']==_HTML_LOCATION_ && empty($input[$_pfields[$field_id]['dbfield'].'_country'])))) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']=$_lang[25];
				$input['error_'.$_pfields[$field_id]['dbfield']]='red_border';
			}
		}

		if (!$error) {
			$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$is_update=false;
			if (mysql_num_rows($res)) {
				$is_update=true;
			}
			if ($is_update) {
				$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='".gmdate('YmdHis')."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`last_changed`='".gmdate('YmdHis')."'";
			}
			if (get_site_option('manual_profile_approval','core')==1) {
				$query.=",`status`='".PSTAT_PENDING."'";
			} else {
				$query.=",`status`='".PSTAT_APPROVED."'";
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
			$nextpage='edit_profile.php';
			$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
			$input['pcat_id']=$pcat_id;
			$topass['input']=$input;
		}
	}
}
redirect2page($nextpage,$topass,$qs);
?>
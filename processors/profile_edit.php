<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/profile_edit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/field_functions.inc.php';
check_login_member('auth');

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_profile.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$pcat_id=sanitize_and_format_gpc($_POST,'pcat_id',TYPE_INT,0,0);
	if (isset($_pcats[$pcat_id]) && count($_pcats[$pcat_id]['fields'])>0) {
		$config=get_site_option(array('manual_profile_approval'),'core');
		$on_changes=array();
		$changes_status=array();
		foreach ($_pcats[$pcat_id]['fields'] as $field_id) {
			$field=&$_pfields[$field_id];
			if ($field->config['editable']) {
				$field->set_value($_POST,true);
				// check for input errors
				if (true!==($temp=$field->validation_server())) {
					$error=true;
					$topass['message']['type']=MESSAGE_ERROR;
					if (empty($temp['text'])) {
						$topass['message']['text']=$GLOBALS['_lang'][69];
					} else {
						$topass['message']['text']=$temp['text'];
					}
					$input['error_'.$field->config['dbfield']]='red_border';
				}
				if (!$error) {
					if (!empty($field->config['changes_status'])) {
						$changes_status[]=$field_id;
					}
					if (!empty($field->config['fn_on_change'])) {
						$on_changes[]=array('fn'=>$field->config['fn_on_change'],
											'param2'=>$field->get_value(),
											'param3'=>$field->config['dbfield']);
					}
				}
			}	// if (is_editable)
		}	// foreach() field of category

		if (!$error) {
			$query="SELECT `fk_user_id`";
			// get the old values of the fields for comparing with new values
			for ($i=0;isset($changes_status[$i]);++$i) {
				$query.=','.$_pfields[$changes_status[$i]]->query_select();
			}
			$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$is_update=false;
			$force_pending=false;
			if (mysql_num_rows($res)) {
				$is_update=true;
				$rsrow=mysql_fetch_assoc($res);
				for ($i=0;isset($changes_status[$i]);++$i) {
					$old_field=(version_compare(PHP_VERSION,'5.0','<')) ? $_pfields[$changes_status[$i]] : clone($_pfields[$changes_status[$i]]);
					$old_field->set_value($rsrow,false);
					if ($old_field->get_value()!=$_pfields[$changes_status[$i]]->get_value()) {
						$force_pending=true;		// if new!=old need to set profile status to pending.
						break;
					}
				}
			}
			if ($is_update) {
				$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='".gmdate('YmdHis')."'";
				if (!empty($config['manual_profile_approval']) && $force_pending) {
					$query.=",`status`=".STAT_PENDING;
				} else {
					//keep it to whatever it was
					//$query.=",`status`=".STAT_APPROVED;
				}
			} else {		// insert here
				$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`last_changed`='".gmdate('YmdHis')."',`_user`='".$_SESSION[_LICENSE_KEY_]['user']['user']."'";
				if (!empty($config['manual_profile_approval'])) {
					$query.=",`status`=".STAT_PENDING;
				} else {
					$query.=",`status`=".STAT_APPROVED;
				}
			}
			foreach ($_pcats[$pcat_id]['fields'] as $field_id) {
				if ($_pfields[$field_id]->config['editable']) {
					$query.=','.$_pfields[$field_id]->query_set();
				}
			}
			if ($is_update) {
				$query.=" WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			// execute the on_change triggers of every field
			for ($i=0;isset($on_changes[$i]);++$i) {
				if (function_exists($on_changes[$i]['fn'])) {
					call_user_func($on_changes[$i]['fn'],$_SESSION[_LICENSE_KEY_]['user']['user_id'],$on_changes[$i]['param2'],$on_changes[$i]['param3']);
				}
			}

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=$GLOBALS['_lang'][100];
		} else {
			$nextpage='profile_edit.php';
			$topass['input']=$_POST;
		}
	}
}
redirect2page($nextpage,$topass,$qs);

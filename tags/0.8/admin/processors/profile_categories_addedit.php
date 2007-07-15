<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/profile_categories_addedit.php
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
require_once '../../includes/tables/profile_categories.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='profile_categories.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($profile_categories_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$profile_categories_default['defaults'][$k]);
	}
	$input['access_level']=!empty($input['access_level']) ? array_sum(array_keys($input['access_level'])) : 0;
	$input['pcat_name']=sanitize_and_format_gpc($_POST,'pcat_name',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	// other formatting
	$input['pcat_name']=ucwords(strtolower($input['pcat_name']));

// check for input errors
	if (empty($input['pcat_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the category!';
		$input['error_pcat_name']='red_border';
	}

	if (!$error) {
		$default_skin_code=get_default_skin_code();
		if (!empty($input['pcat_id'])) {
			$query="UPDATE `{$dbtable_prefix}profile_categories` SET ";
			foreach ($profile_categories_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `pcat_id`='".$input['pcat_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['fk_lk_id_pcat']."' AND `skin`='$default_skin_code'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."' WHERE `fk_lk_id`='".$input['fk_lk_id_pcat']."' AND `skin`='$default_skin_code'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."',`fk_lk_id`='".$input['fk_lk_id_pcat']."',`skin`='$default_skin_code'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			regenerate_fields_array();
			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Category changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`=".FIELD_TEXTFIELD.",`lk_diz`='Category name',`lk_use`='".LK_FIELD."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_pcat']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."',`fk_lk_id`='".$input['fk_lk_id_pcat']."',`skin`='$default_skin_code'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$query="INSERT INTO `{$dbtable_prefix}profile_categories` SET ";
			foreach ($profile_categories_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			regenerate_fields_array();
			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Category added.';
		}
	} else {
		$nextpage='profile_categories_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

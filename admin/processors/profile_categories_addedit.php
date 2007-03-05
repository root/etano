<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/profile_categories_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/profile_categories.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/profile_categories.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($profile_categories_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__html2type[$v],$__html2format[$v],$profile_categories_default['defaults'][$k]);
	}
	$input['access_level']=!empty($input['access_level']) ? array_sum(array_keys($input['access_level'])) : 0;

	$input['pcat_name']=sanitize_and_format_gpc($_POST,'pcat_name',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');

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
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['fk_lk_id_pcat']."' AND `skin`='"._DEFAULT_SKIN_."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."' WHERE `fk_lk_id`='".$input['fk_lk_id_pcat']."' AND `skin`='"._DEFAULT_SKIN_."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."',`fk_lk_id`='".$input['fk_lk_id_pcat']."',`skin`='"._DEFAULT_SKIN_."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

			regenerate_fields_array();
			regenerate_langstrings_array();

			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Category changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `lk_type`="._HTML_TEXTFIELD_.",`lk_diz`='Category name'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['fk_lk_id_pcat']=mysql_insert_id();
			$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['pcat_name']."',`fk_lk_id`='".$input['fk_lk_id_pcat']."',`skin`='"._DEFAULT_SKIN_."'";
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
		$nextpage='admin/profile_categories_addedit.php';
// 		you must replace '\r' and '\n' strings with <enter> in all textareas like this:
//		$input['x']=preg_replace(array('/([^\\\])\\\n/','/([^\\\])\\\r/'),array("$1\n","$1"),$input['x']);
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
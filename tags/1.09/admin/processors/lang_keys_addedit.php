<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/lang_keys_addedit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='lang_strings.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['lk_id']=!empty($_POST['lk_id']) ? (int)$_POST['lk_id'] : 0;
	$input['alt_id_text']=sanitize_and_format_gpc($_POST,'alt_id_text',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['lk_type']=!empty($_POST['lk_type']) ? (int)$_POST['lk_type'] : FIELD_TEXTFIELD;
	$input['lk_diz']=sanitize_and_format_gpc($_POST,'lk_diz',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['save_file']=sanitize_and_format_gpc($_POST,'save_file',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		if (!empty($input['lk_id'])) {
			$query="UPDATE `{$dbtable_prefix}lang_keys` SET `alt_id_text`='".$input['alt_id_text']."',`lk_type`=".$input['lk_type'].",`lk_diz`='".$input['lk_diz']."',`lk_use`=".LK_MESSAGE.",`save_file`='".$input['save_file']."' WHERE `lk_id`=".$input['lk_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=sprintf('Key %s changed',$input['lk_id']);
		} else {
			$query="INSERT INTO `{$dbtable_prefix}lang_keys` SET `alt_id_text`='".$input['alt_id_text']."',`lk_type`=".$input['lk_type'].",`lk_diz`='".$input['lk_diz']."',`lk_use`=".LK_MESSAGE.",`save_file`='".$input['save_file']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['lk_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']=sprintf('Key %s added',$input['lk_id']);
		}
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage.'#bottom';
redirect2page($nextpage,$topass,'',true);

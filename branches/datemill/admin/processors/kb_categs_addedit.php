<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/kb_categs_addedit.php
$Revision: 219 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/kb_categs.inc.php';
allow_dept(DEPT_ADMIN);

$dbtable_prefix='';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='kb_categs.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($kb_categs_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v] | FORMAT_RUDECODE,$kb_categs_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['kbc_title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the title!';
		$input['error_kbc_title']='red_border';
	}

	if (!$error) {
		if (!empty($input['kbc_id'])) {
			$query="UPDATE `{$dbtable_prefix}kb_categs` SET ";
			foreach ($kb_categs_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `kbc_id`=".$input['kbc_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Category changed.';
		} else {
			unset($input['kbc_id']);
			$query="INSERT INTO `{$dbtable_prefix}kb_categs` SET ";
			foreach ($kb_categs_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Category added.';
		}
	} else {
		$nextpage='kb_categs_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=isset($input['return']) ? rawurlencode($input['return']) : '';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
if (!isset($_POST['silent'])) {
	$nextpage=_BASEURL_.'/admin/'.$nextpage;
	redirect2page($nextpage,$topass,'',true);
} else {
	$output='';
	if (!$error) {
		$output=$input['fk_kbc_id_parent'];
	} else {
		$output='0|'.$topass['message']['text'];
	}
	echo $output;
	die;
}

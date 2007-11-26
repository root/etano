<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/package_ui.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);
set_time_limit(0);
ignore_user_abort(true);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/package_install.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['package_path']=sanitize_and_format_gpc($_POST,'package_path',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['install_index']=isset($_POST['install_index']) ? (int)$_POST['install_index'] : -1;
	$input['processor']=sanitize_and_format_gpc($_POST,'processor',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$temp=explode('/',$input['package_path']);
	$zip_name=$temp[count($temp)-1].'.zip';
	$qs.=$qs_sep.'f='.$zip_name;
	$qs_sep='&';

	if (empty($input['package_path'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Required parameter \'package_path\' not received from user input!';
	}
	if ($input['install_index']==-1) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Required parameter \'install_index\' not received from user input!';
	}
	if (empty($input['processor'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Required parameter \'processor\' not received from user input!';
	}
	if (!is_file($input['package_path'].'/'.$input['processor'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]=sprintf('Could not find the processor file: %s',$input['package_path'].'/'.$input['processor']);
	}

	if (!$error) {
		require_once $input['package_path'].'/'.$input['processor'];
	}
	if (!$error) {
		$qs.=$qs_sep.'skip_input='.$input['install_index'];
		$qs_sep='&';
	} else {
		$nextpage='admin/package_install.php';
		$qs.=$qs_sep.'ui_error='.$input['install_index'];
		$qs_sep='&';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);

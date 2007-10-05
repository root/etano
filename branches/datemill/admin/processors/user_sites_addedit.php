<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/user_sites_addedit.php
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
require_once '../../includes/tables/user_sites.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='user_products.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($user_sites_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_sites_default['defaults'][$k]);
	}
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format($_POST['return'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE);
		$nextpage=$input['return'];
	} else {
		$nextpage.='?uid='.$input['fk_user_id'];
	}

// check for input errors
	if (empty($input['fk_user_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No user selected';
		$input['error_fk_prod_id']='red_border';
	}

	if (empty($input['license'])) {
		$input['license_md5']='';
	} else {
		$input['license_md5']=md5($input['license']);
	}

	if (!$error) {
		if (!empty($input['site_id'])) {
			$query="UPDATE `user_sites` SET ";
			foreach ($user_sites_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `site_id`=".$input['site_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Site changed.';
		} else {
			unset($input['site_id']);
			$query="INSERT INTO `user_sites` SET ";
			foreach ($user_sites_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Site added for the specified user.';
		}
	} else {
		$nextpage='user_sites_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=isset($input['return']) ? rawurlencode($input['return']) : '';
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/site_edit.php
$Revision: 320 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/tables/user_sites.inc.php';
require_once '../includes/classes/fileop.class.php';
check_login_member('auth');

if (is_file(_BASEPATH_.'/events/processors/site_edit.php')) {
	include_once _BASEPATH_.'/events/processors/site_edit.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_sites.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	unset($user_sites_default['types']['screenshot']);
	foreach ($user_sites_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_sites_default['defaults'][$k]);
	}

	$screenshot=upload_file(_BASEPATH_.'/tmp','screenshot',$input['site_id']);
	if (!empty($screenshot)) {
		$ext=strtolower(substr(strrchr($screenshot,'.'),1));
		if (in_array($ext,$accepted_images)) {
			$input['screenshot']=$input['site_id'].'.'.$ext;
		}
	}
	$input['fk_user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
	$input['return']='';
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['site_id'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid site entered.';
	}
	if (empty($input['baseurl']) || $input['baseurl']=='http://') {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the url of the site.';
	}

	if (!$error) {
		$temp=@parse_url($input['baseurl']);
		if (!$temp || empty($temp['scheme']) || $temp['scheme']!='http' || !empty($temp['query']) || !empty($temp['fragment'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid url specified. The url must start with http:// and contain a valid web site address.';
		}
		if (!$error && $temp['host']==gethostbyname($temp['host'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Could not resolve host name. Please re-check the entered url address.';
		}
	}

	if (!$error) {
		$query="SELECT `baseurl` FROM `user_sites` WHERE `site_id`=".$input['site_id']." AND `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$baseurl=mysql_result($res,0,0);
			if (!empty($baseurl) && $baseurl!=$input['baseurl']) {
				$query="INSERT INTO `user_sites_older` (`fk_user_id`,`fk_site_id`,`baseurl`) VALUES (".$_SESSION[_LICENSE_KEY_]['user']['user_id'].",".$input['site_id'].",'$baseurl')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid site entered.';
		}
	}

	if (!$error) {
		if (!empty($input['screenshot'])) {
			$fileop=new fileop();
			if (is_file(_PHOTOPATH_.'/sites/'.$input['screenshot'])) {
				$fileop->delete(_PHOTOPATH_.'/sites/'.$input['screenshot']);
			}
			if (!$fileop->rename(_BASEPATH_.'/tmp/'.$screenshot,_PHOTOPATH_.'/sites/'.$input['screenshot'])) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Could not move the screenshot file to the final destination.';
				$fileop->delete(_BASEPATH_.'/tmp/'.$screenshot);
				unset($input['screenshot']);
			}
		}

		$query="UPDATE `user_sites` SET ";
		foreach ($user_sites_default['defaults'] as $k=>$v) {
			if (isset($input[$k])) {
				$query.="`$k`='".$input[$k]."',";
			}
		}
		$query=substr($query,0,-1);
		$query.=" WHERE `site_id`=".$input['site_id']." AND `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		if (isset($_on_before_update)) {
			for ($i=0;isset($_on_before_update[$i]);++$i) {
				call_user_func($_on_before_update[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='License details changed.';     // translate
		if (isset($_on_after_update)) {
			for ($i=0;isset($_on_after_update[$i]);++$i) {
				call_user_func($_on_after_update[$i]);
			}
		}
	} else {
		$nextpage='site_edit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				call_user_func($_on_error[$i]);
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

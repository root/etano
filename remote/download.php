<?php
/******************************************************************************
datemill.com
===============================================================================
File:                       remote/download.php
$Revision: 193 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

$input=array();
$input['lk']=sanitize_and_format_gpc($_GET,'lk',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$input['id']=isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!empty($input['lk']) && !empty($input['id']) && isset($_GET['t'])) {
	if ($_GET['t']=='u') {
		$table='updates';
		$key='update_id';
	} elseif ($_GET['t']=='p') {
		$table='products';
		$key='prod_id';
	} else {
		die('Invalid download requested');
	}
	$query="SELECT `site_id`,`baseurl`,`active` FROM `user_sites` WHERE `license_md5`='".$input['lk']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$input=array_merge($input,mysql_fetch_assoc($res));
		if (empty($input['active'])) {
			die('Sorry, you\'re not allowed to download from our server.');
		}
		if (empty($input['baseurl'])) {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter your site URL first.';
			redirect2page('site_edit.php',$topass,'site_id='.$input['site_id']);
		}
	}

	if ($_GET['t']=='p') {
		$query="SELECT `fk_user_id` FROM `user_products` WHERE `fk_site_id`=".$input['site_id']." AND `fk_prod_id`=".$input['id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_num_rows($res)) {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='You need to buy this product first.';
			redirect2page('purchase.php',$topass);
		}
	}

	$query="SELECT `filename` FROM `$table` WHERE `$key`=".$input['id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$filename=mysql_result($res,0,0);
		if (is_file(_BASEPATH_.'/dafilez/'.$table.'/'.$filename)) {
			$size=filesize(_BASEPATH_.'/dafilez/'.$table.'/'.$filename);
			header('Content-Type: application/octet-stream; name="'.$filename.'"'); //This should work for Non IE/Opera browsers
			header('Content-Type: application/octetstream; name="'.$filename.'"'); // This should work for IE & Opera
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-transfer-encoding: binary");
			header('Content-length: '.$size);
			readfile(_BASEPATH_.'/dafilez/'.$table.'/'.$filename);
		}
	}
} else {
	die('Invalid parameters received');
}

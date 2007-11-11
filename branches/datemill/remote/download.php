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
		// to download updates we must know their baseurl
		if (empty($input['baseurl']) && $_GET['t']=='u') {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter your site URL first.';
			redirect2page('site_edit.php',$topass,'site_id='.$input['site_id']);
		}
	} else {
		die('Sorry, we cannot figure out who you are. Please contact us if you think this is an error.');
	}

	if ($_GET['t']=='p') {
		$query="SELECT `uprod_id` FROM `user_products` WHERE `fk_site_id`=".$input['site_id']." AND `fk_prod_id`=".$input['id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (!mysql_num_rows($res)) {
			// so they do not have this product listed as purchased but maybe they're trying to download a
			// bundle. Let's see if this prod is a bundle of already purchased prods
			$query="SELECT `bundle_of` FROM `products` WHERE `prod_id`=".$input['id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$prods=explode('|',mysql_result($res,0,0));
				if (!empty($prods)) {
					$query="SELECT a.`uprod_id`,b.`filename` FROM `user_products` a LEFT JOIN `products` b ON a.`fk_prod_id`=b.`prod_id` WHERE a.`fk_site_id`=".$input['site_id']." AND a.`fk_prod_id` IN ('".join("','",$prods)."')";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$dlds=array();
					$uprod_ids=array();
					while ($rsrow=mysql_fetch_assoc($res)) {
						$uprod_ids[]=$rsrow['uprod_id'];
						if (!empty($rsrow['filename'])) {
							$dlds[]=$rsrow['uprod_id'];
						}
					}
					if (count($uprod_ids)==count($prods)) {
						// what dayaknow, it is a bundle of purchased prods!
						$query="UPDATE `user_products` SET `downloads`=`downloads`+1,`last_download`=now() WHERE `uprod_id` IN ('".join("','",$dlds)."')";
						if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					} else {
						// it is a bundle but they don't own all prods of the bundle.
						$topass['message']['type']=MESSAGE_ERROR;
						$topass['message']['text']='You need to buy this product first.';
						redirect2page('purchase.php',$topass);
					}
				} else {
					// this is not a bundle. They really are trying to download a product they didn't purchase
					$topass['message']['type']=MESSAGE_ERROR;
					$topass['message']['text']='You need to buy this product first.';
					redirect2page('purchase.php',$topass);
				}
			} else {
				// not even a product. Let's redirect them to purchase anyway, maybe they change their mind
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='You need to buy this product first.';
				redirect2page('purchase.php',$topass);
			}
		} else {
			$query="UPDATE `user_products` SET `downloads`=`downloads`+1,`last_download`=now() WHERE `uprod_id`=".mysql_result($res,0,0);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
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
		} else {
			die('No file to download for this product.');
		}
	}
} else {
	die('Invalid parameters received');
}

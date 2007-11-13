<?php
/******************************************************************************
datemill.com
===============================================================================
File:                       remote/ident.php
$Revision: 193 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
if (!isset($ident_return)) {
	db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
}

$output=array();
$input=array();

$input['lk']=sanitize_and_format_gpc($_REQUEST,'lk',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$input['bu']=base64_decode(sanitize_and_format_gpc($_REQUEST,'bu',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
$input['v']=sanitize_and_format_gpc($_REQUEST,'v',TYPE_FLOAT,0,0);
$input['site_id']=0;

if (!empty($input['lk']) && !empty($input['bu'])) {
	$query="SELECT `site_id`,`fk_user_id`,`baseurl` FROM `user_sites` WHERE `license_md5`='".$input['lk']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$input=array_merge($input,mysql_fetch_assoc($res));
		if ($input['baseurl']!=$input['bu']) {
			$query="UPDATE `user_sites` SET `baseurl`='".$input['bu']."' WHERE `site_id`=".$input['site_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($input['baseurl'])) {
				$query="INSERT INTO `user_sites_older` SET `fk_user_id`=".$input['fk_user_id'].",`fk_site_id`=".$input['site_id'].",`baseurl`='".$input['baseurl']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
			// we should also send an email warning to admin
		}
	} else {
		// no product with this license exists!!! MUST SAVE THIS INFO TO DB
		$output['smelly']=true;
	}
} else {
	// no license or baseurl provided!! What do you have to hide? MUST SAVE THIS INFO TO DB
	$output['smelly']=true;
}

if (isset($ident_return)) {	// this is set only if this file is included from somewhere else.
	return $input['site_id'];
} else {
	echo $input['site_id'];
}

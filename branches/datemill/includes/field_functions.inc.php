<?php
/******************************************************************************
Etano
===============================================================================
File:                       includes/field_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

function change_email($user_id,$field,$field_name='') {
	if (preg_match('/[a-zA-Z0-9\.\-_]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z0-9\.\-]+/',$field)) {
		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `email`='$field' WHERE `".USER_ACCOUNT_ID."`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}


function update_location($user_id,$field,$field_name='') {
	global $dbtable_prefix;
	$latitude=0;
	$longitude=0;
	$state_id=0;
	$city_id=0;
	if (!empty($field['city']) && empty($field['zip'])) {
		$query="SELECT `latitude`,`longitude` FROM `{$dbtable_prefix}loc_cities` WHERE `city_id`='".$field['city']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($latitude,$longitude)=mysql_fetch_row($res);
		}
	} elseif (!empty($field['zip'])) {
		$query="SELECT `latitude`,`longitude`,`fk_state_id`,`fk_city_id` FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$field['zip']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($latitude,$longitude,$state_id,$city_id)=mysql_fetch_row($res);
		}
	}
	if (!empty($latitude) || !empty($longitude) || !empty($state_id) || !empty($city_id)) {
		$query="UPDATE `{$dbtable_prefix}user_profiles` SET ";
		if (!empty($latitude)) {
			$query.="`latitude`='$latitude',";
		}
		if (!empty($longitude)) {
			$query.="`longitude`='$longitude',";
		}
		if (!empty($state_id)) {
			$query.="`{$field_name}_state`=$state_id,";
		}
		if (!empty($state_id)) {
			$query.="`{$field_name}_city`=$city_id,";
		}
		$query=substr($query,0,-1)." WHERE `fk_user_id`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

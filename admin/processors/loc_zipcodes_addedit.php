<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/loc_zipcodes_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/loc_zips.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/loc_zipcodes.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($zips_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$zips_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['zipcode'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the zip/postal code';
		$input['error_zipcode']='red_border';
	}
	if (empty($input['latitude'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the latitude';
		$input['error_latitude']='red_border';
	}
	if (empty($input['longitude'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the longitude';
		$input['error_longitude']='red_border';
	}

	if (!$error) {
		if (!empty($input['zip_id'])) {
			$query="UPDATE `{$dbtable_prefix}loc_zips` SET ";
			foreach ($zips_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (isset($input['latitude'])) {
				$query.=",`rad_latitude`=radians(".$input['latitude'].")";
			}
			if (isset($input['longitude'])) {
				$query.=",`rad_longitude`=radians(".$input['longitude'].")";
			}
			$query.=" WHERE `zip_id`=".$input['zip_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Zip code changed.';
		} else {
			unset($input['zip_id']);
			$query="INSERT INTO `{$dbtable_prefix}loc_zips` SET ";
			foreach ($zips_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (isset($input['latitude'])) {
				$query.=",`rad_latitude`=radians(".$input['latitude'].")";
			}
			if (isset($input['longitude'])) {
				$query.=",`rad_longitude`=radians(".$input['longitude'].")";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Zip code added.';
		}
	} else {
		$nextpage='admin/loc_zipcodes_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
	$qs.=$qs_sep.'city_id='.$input['fk_city_id'];
	$qs_sep='&';
	$qs.=$qs_sep.'state_id='.$input['fk_state_id'];
	$qs.=$qs_sep.'country_id='.$input['fk_country_id'];
	if (isset($_POST['o'])) {
		$qs.=$qs_sep.'o='.$_POST['o'];
	}
	if (isset($_POST['r'])) {
		$qs.=$qs_sep.'r='.$_POST['r'];
	}
	if (isset($_POST['cio'])) {
		$qs.=$qs_sep.'cio='.$_POST['cio'];
	}
	if (isset($_POST['cir'])) {
		$qs.=$qs_sep.'cir='.$_POST['cir'];
	}
	if (isset($_POST['so'])) {
		$qs.=$qs_sep.'so='.$_POST['so'];
	}
	if (isset($_POST['sr'])) {
		$qs.=$qs_sep.'sr='.$_POST['sr'];
	}
	if (isset($_POST['co'])) {
		$qs.=$qs_sep.'co='.$_POST['co'];
	}
	if (isset($_POST['cr'])) {
		$qs.=$qs_sep.'cr='.$_POST['cr'];
	}
}
redirect2page($nextpage,$topass,$qs);

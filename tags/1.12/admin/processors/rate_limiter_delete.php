<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/rate_limiter_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$rate_id=isset($_GET['rate_id']) ? (int)$_GET['rate_id'] : 0;

$query="SELECT `fk_lk_id_error_message` FROM `{$dbtable_prefix}rate_limiter` WHERE `rate_id`=$rate_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$lk_id=mysql_result($res,0,0);
	$query="DELETE FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`=$lk_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}lang_keys` WHERE `lk_id`=$lk_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$query="DELETE FROM `{$dbtable_prefix}rate_limiter` WHERE `rate_id`=$rate_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	regenerate_langstrings_array();

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Limit removed successfully.';
}

redirect2page('admin/rate_limiter.php',$topass,$qs);

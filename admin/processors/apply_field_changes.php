<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/apply_field_changes.php
$Revision: 221 $
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

regenerate_fields_array();
regenerate_langstrings_array();
unset($GLOBALS['_pfields'],$GLOBALS['_pcats'],$GLOBALS['basic_search_fields']);
require '../../includes/fields.inc.php';
regenerate_skin_cache();

$query="SELECT `dbfield`,`field_type`,`search_type` FROM `{$dbtable_prefix}profile_fields` WHERE `searchable`=1 AND `for_basic`=1 ORDER BY `order_num`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$fields=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	if ($rsrow['field_type']==FIELD_LOCATION) {
		$fields[]=$rsrow['dbfield'].'_country';
	} elseif ($rsrow['field_type']==FIELD_CHECKBOX_LARGE) {
	} else {
		$fields[]=$rsrow['dbfield'];
	}
}

$query="ALTER TABLE `{$dbtable_prefix}user_profiles` DROP INDEX `searchkey`";
@mysql_query($query);

if (!empty($fields)) {
	$query="ALTER TABLE `{$dbtable_prefix}user_profiles` ADD INDEX `searchkey` (`".join("`,`",$fields)."`)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Field and category changes applied successfully.';
redirect2page('admin/profile_fields.php',$topass);

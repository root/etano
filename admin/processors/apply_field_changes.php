<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/apply_field_changes.php
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
set_time_limit(0);

regenerate_fields_array();
if (empty($_GET['fields_only'])) {
	regenerate_langstrings_array();

	$query="SELECT `dbfield`,`field_type` FROM `{$dbtable_prefix}profile_fields2` WHERE `searchable`=1 AND `for_basic`=1 ORDER BY `order_num`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$fields=array();
	// this should be rewritten with the new pfields system...
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['field_type']=='field_location' || $rsrow['field_type']=='field_loc_req') {
			$fields[]=$rsrow['dbfield'].'_country';
		} elseif ($rsrow['field_type']=='field_mchecks') {
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

	unset($GLOBALS['_pfields'],$GLOBALS['_pcats'],$GLOBALS['basic_search_fields']);
	require _BASEPATH_.'/skins_site/'.$def_skin.'/lang/global.inc.php';
	require _BASEPATH_.'/includes/fields.inc.php';
	regenerate_skin_cache();
}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']='Field and category changes applied successfully.';
redirect2page('admin/profile_fields.php',$topass);

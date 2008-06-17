<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/ajax/search_custom_config.php
$Revision: 610 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/common.inc.php';
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$towrite='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$output=array();
	$pfield_id=sanitize_and_format_gpc($_POST,'pfield_id',TYPE_INT,0,0);
	$search_type=sanitize_and_format_gpc($_POST,'search_type',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	if (!empty($pfield_id)) {
		$query="SELECT `pfield_id`,`fk_lk_id_label`,`field_type`,`searchable`,`search_type`,`for_basic`,`fk_lk_id_search`,`at_registration`,`reg_page`,`required`,`editable`,`visible`,`dbfield`,`fk_lk_id_help`,`fk_pcat_id`,`custom_config`,`fn_on_change`,`order_num` FROM `{$dbtable_prefix}profile_fields2` WHERE `pfield_id`=$pfield_id";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=mysql_fetch_assoc($res);
			$temp=unserialize($output['custom_config']);
			unset($output['custom_config']);
			if (is_array($temp)) {
				$output=array_merge($output,$temp);
			}
		}
	}
	// we need this as a global var because it is used by $search_field->edit_admin()
	$GLOBALS['output']=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);

	$search_field=null;
	if (class_exists($search_type)) {
		$search_field=new $search_type;
		$towrite=$search_field->edit_admin('search');
	}
}

echo $towrite;

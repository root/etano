<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/regenerate_skin.php
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$skin=sanitize_and_format_gpc($_GET,'s',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$last_id=isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

if (!$error) {
	if ($last_id==0) {
		regenerate_langstrings_array($skin);
	}
	regenerate_skin_cache($skin,$last_id);

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='All files in the skin were regenerated.';
}
redirect2page('admin/site_skins.php',$topass,$qs);

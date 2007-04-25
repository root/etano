<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/ipn.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';

$module_code=preg_replace('[^a-zA-Z0-9_]','',sanitize_and_format_gpc($_REQUEST,'p',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
if (!empty($module_code)) {
	if (is_file(_BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php')) {
		include_once _BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php';
		$class='payment_'.$module_code;
		$pay=new $class;
		$pay->ipn();
	}
}
?>
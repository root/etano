<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/ipn.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/payment.inc.php';

$module_code=preg_replace('[^a-zA-Z0-9_]','',sanitize_and_format_gpc($_REQUEST,'p',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
if (!empty($module_code)) {
	if (is_file(_BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php')) {
		include _BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php';
		$class='payment_'.$module_code;
		$pay=new $class;
		$pay->ipn();
	} else {
		require _BASEPATH_.'/includes/classes/log_error.class.php';
		new log_error(array('module_name'=>'ipn','text'=>'Received a payment IPN for unexisting module: $_REQUEST:'.var_export($_REQUEST,true)));
	}
} else {
	require _BASEPATH_.'/includes/classes/log_error.class.php';
	new log_error(array('module_name'=>'ipn','text'=>'Received a payment IPN without module code: $_REQUEST:'.var_export($_REQUEST,true)));
}

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       thankyou.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(2);

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$module_code=preg_replace('[^a-zA-Z0-9_]','',sanitize_and_format_gpc($_REQUEST,'p',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
if (!empty($module_code)) {
	if (is_file(_BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php')) {
		include_once _BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php';
		$class='payment_'.$module_code;
		$pay=new $class;
		$pay->thankyou($tpl);
	}
}


print_r($_REQUEST);
$tpl->set_file('content','thankyou.html');
//$tpl->set_var('body',$body);
$tpl->process('content','content');

$tplvars['title']='Thank you for your payment';
include 'frame.php';
?>
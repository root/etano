<?php
/******************************************************************************
Etano
===============================================================================
File:                       thankyou.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/payment.inc.php';
check_login_member('auth');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');
$tpl->set_file('content','thankyou.html');

$module_code=preg_replace('[^a-zA-Z0-9_]','',sanitize_and_format_gpc($_REQUEST,'p',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
if (!empty($module_code)) {
	if (is_file(_BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php')) {
		include_once _BASEPATH_.'/plugins/payment/'.$module_code.'/'.$module_code.'.class.php';
		$class='payment_'.$module_code;
		$pay=new $class;
		$pay->thankyou($tpl);
	}
}

$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][249];
$tplvars['page_title']=$GLOBALS['_lang'][249];
$tplvars['page']='thankyou';
$tplvars['css']='thankyou.css';
if (is_file('thankyou_left.php')) {
	include 'thankyou_left.php';
}
$no_timeout=true;
include 'frame.php';

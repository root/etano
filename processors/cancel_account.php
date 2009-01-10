<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/cancel_account.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
check_login_member('auth');

if (is_file(_BASEPATH_.'/events/processors/cancel_account.php')) {
	include _BASEPATH_.'/events/processors/cancel_account.php';
}

$query="UPDATE `{$dbtable_prefix}user_profiles` SET `del`=1 WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
if (isset($_on_before_update)) {
	for ($i=0;isset($_on_before_update[$i]);++$i) {
		call_user_func($_on_before_update[$i]);
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (isset($_on_after_update)) {
	for ($i=0;isset($_on_after_update[$i]);++$i) {
		call_user_func($_on_after_update[$i]);
	}
}
$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']=$GLOBALS['_lang'][275];
redirect2page('info.php',$topass);

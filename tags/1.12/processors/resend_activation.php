<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/resend_activation.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/join.inc.php';

$qs='type=signup';
$qssep='&';

$uid=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
if (!empty($uid)) {
	$query="SELECT `".USER_ACCOUNT_ID."` as `uid`,`email`,`temp_pass` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."`=$uid";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$input=mysql_fetch_assoc($res);
		if (empty($input['temp_pass'])) {
			$input['temp_pass']=gen_pass(7);
			$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `temp_pass`='".$input['temp_pass']."' WHERE `".USER_ACCOUNT_ID."`=$uid";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		send_template_email($input['email'],sprintf($GLOBALS['_lang'][70],_SITENAME_),'confirm_reg.html',get_my_skin(),$input);
		$qs.=$qssep.'email='.$input['email'];
	}
}
redirect2page('info.php',array(),$qs);

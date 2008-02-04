<?php
/******************************************************************************
Etano
===============================================================================
File:                       account_confirm.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/join.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$uid=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$secret=sanitize_and_format_gpc($_GET,'secret',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

if (!empty($uid) && !empty($secret)) {
	$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `status`=".ASTAT_ACTIVE.",`temp_pass`='".gen_pass(7)."' WHERE `".USER_ACCOUNT_ID."`=$uid AND `status`=".ASTAT_UNVERIFIED." AND `temp_pass`='$secret' LIMIT 1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (isset($_SESSION[_LICENSE_KEY_]['user']['timedout'])) {
		unset($_SESSION[_LICENSE_KEY_]['user']['timedout']);
	}
	if (mysql_affected_rows()) {
		$qs='type=acctok';
		redirect2page('info.php',array(),$qs);
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][1];
		redirect2page('info.php',$topass);
	}
}

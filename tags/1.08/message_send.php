<?php
/******************************************************************************
Etano
===============================================================================
File:                       message_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('message_write');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=$user_inbox_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
	unset($_SESSION['topass']['input']);
} elseif (!empty($_GET['to_id'])) {
	$output['fk_user_id']=(int)$_GET['to_id'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} elseif (!empty($_GET['mail_id'])) {
	$mail_id=(int)$_GET['mail_id'];
	$query="SELECT `mail_id`,`fk_user_id_other` as `fk_user_id`,`subject`,`message_body`,`_user_other`,`message_type` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`=$mail_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['subject']=(substr($output['subject'],0,3)=='Re:') ? $output['subject'] : 'Re: '.$output['subject'];
		if ($output['message_type']==MESS_MESS) {
			$output['message_body']="\n\n[quote]".$output['message_body'].'[/quote]';
		} else {
			$output['message_body']='';
		}
	}
	if (isset($_GET['gettpl'])) {
		$tpl_id=(int)$_GET['gettpl'];
		$query="SELECT `subject`,`message_body` FROM `{$dbtable_prefix}user_mtpls` WHERE `mtpl_id`=$tpl_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=array_merge($output,mysql_fetch_assoc($res));
		}
	}
// no need to sanitize
//	$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	if ($output['message_type']==MESS_FLIRT) {
		$output['flirt_reply']=true;
	}
} else {
	trigger_error($GLOBALS['_lang'][120],E_USER_ERROR);
}
$output['lang_263']=sanitize_and_format($GLOBALS['_lang'][263],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_264']=sanitize_and_format($GLOBALS['_lang'][264],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_196']=sanitize_and_format($GLOBALS['_lang'][196],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_197']=sanitize_and_format($GLOBALS['_lang'][197],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUENCODE,'');
}

$output['bbcode_message']=get_site_option('bbcode_message','core');
if (empty($output['bbcode_message'])) {
	unset($output['bbcode_message']);
}
$tpl->set_file('content','message_send.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['page_title']=sprintf($GLOBALS['_lang'][137],$output['_user_other']);
$tplvars['title']=$tplvars['page_title'];
$tplvars['page']='message_send';
$tplvars['css']='message_send.css';
if (is_file('message_send_left.php')) {
	include 'message_send_left.php';
}
include 'frame.php';

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
	$query="SELECT `mail_id`,`fk_user_id_other` as `fk_user_id`,`subject`,`message_body`,`_user_other`,`message_type` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`=$mail_id AND `fk_user_id`=".$_SESSION['user']['user_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['subject']=(substr($output['subject'],0,3)=='Re:') ? $output['subject'] : 'Re: '.$output['subject'];
		$output['message_body']="\n\n[quote]".$output['message_body'].'[/quote]';
		if ($output['message_type']!=MESS_MESS) {
			$output['message_body']='';
		}
	}
	if (isset($_GET['gettpl'])) {
		$tpl_id=(int)$_GET['gettpl'];
		$query="SELECT `subject`,`message_body` FROM `{$dbtable_prefix}user_mtpls` WHERE `mtpl_id`=$tpl_id AND `fk_user_id`=".$_SESSION['user']['user_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=array_merge($output,mysql_fetch_assoc($res));
		}
	}
	$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	if ($output['message_type']==MESS_FLIRT) {
		$output['flirt_reply']=true;
	}
} else {
	trigger_error('No receiver specified',E_USER_ERROR);     // translate
}

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

$tplvars['title']='Contact a member';     // translate
$tplvars['page_title']=sprintf('Write to %s',$output['_user_other']);	// translate
$tplvars['page']='message_send';
$tplvars['css']='message_send.css';
if (is_file('message_send_left.php')) {
	include 'message_send_left.php';
}
include 'frame.php';

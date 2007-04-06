<?php
/******************************************************************************
newdsb
===============================================================================
File:                       message_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/vars.inc.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=$user_inbox_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} elseif (isset($_GET['to_id']) && !empty($_GET['to_id'])) {
	$output['fk_user_id']=(int)$_GET['to_id'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} elseif (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
	$mail_id=(int)$_GET['mail_id'];
	$query="SELECT `mail_id`,`fk_user_id_other` as `fk_user_id`,`subject`,`message_body`,`_user_other` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`='$mail_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['subject']=(substr($output['subject'],0,3)=='Re:') ? $output['subject'] : 'Re: '.$output['subject'];
		$output['message_body']="\n\n[quote]".$output['message_body'].'[/quote]';
	}
	$output=sanitize_and_format($output,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
} else {
	trigger_error('No receiver specified',E_USER_ERROR);     // translate
}

if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD] | FORMAT_RUENCODE,'');
}

$tplvars['bbcode_message']=get_site_option('bbcode_message','core');
if (empty($tplvars['bbcode_message'])) {
	unset($tplvars['bbcode_message']);
}
$tpl->set_file('content','message_send.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Contact a member';     // translate
$tplvars['page_title']=sprintf('Write to %s',$output['_user_other']);	// translate
$tplvars['page']='message_send';
$tplvars['css']='message_send.css';
if (is_file('message_send_left.php')) {
	include 'message_send_left.php';
}
include 'frame.php';
?>
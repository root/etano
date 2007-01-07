<?php
/******************************************************************************
newdsb
===============================================================================
File:                       message_send.php
$Revision: 85 $
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
require_once 'includes/tables/user_inbox.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(5);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$mail=$user_inbox_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$mail=$_SESSION['topass']['input'];
	$mail['to_name']=get_user_by_userid($mail['fk_user_id']);
} elseif (isset($_GET['to_id']) && !empty($_GET['to_id'])) {
	$mail['fk_user_id']=(int)$_GET['to_id'];
	$mail['to_name']=get_user_by_userid($mail['fk_user_id']);
} elseif (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
	$mail_id=(int)$_GET['mail_id'];
	$query="SELECT b.`fk_user_id`,a.`subject`,a.`message_body`,a.`_user_from` as `to_name` FROM `{$dbtable_prefix}user_inbox` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id_from`=b.`fk_user_id` AND a.`mail_id`='$mail_id' AND a.`fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($mail['fk_user_id'],$mail['subject'],$mail['message_body'],$mail['to_name'])=mysql_fetch_row($res);
		$mail['message_body']="\n\n[quote]".$mail['message_body'].'[/quote]';
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='The sender of this message could not be found.';
		$qs='mail_id='.$mail_id;
		redirect2page('message_read.php',$topass,$qs);
	}
	$mail=sanitize_and_format($mail,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
} else {
	trigger_error('No receiver specified',E_USER_ERROR);
}

$tpl->set_file('content','message_send.html');
$tpl->set_var('mail',$mail);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['ob'])) {
	$tpl->set_var('ob',$_GET['ob']);
}
if (isset($_GET['od'])) {
	$tpl->set_var('od',$_GET['od']);
}
$tpl->process('content','content');

$tplvars['title']='Contact a member';
include 'frame.php';
?>
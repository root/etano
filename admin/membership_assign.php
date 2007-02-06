<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/membership_assign.php
$Revision: 29 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
	$output['search']=sanitize_and_format_gpc($_REQUEST,'search',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search']."' AND `search_type`='"._SEARCH_USER_."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$output['uids']=explode(',',$results);
	}
} elseif (isset($_REQUEST['uids']) && !empty($_REQUEST['uids'])) {
	$output['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
}
$output['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
$output['return2']=rawurldecode($output['return']);

if (!empty($output['uids'])) {
	$output['m_value']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`',4,'`m_value`<>1');
	$query="SELECT `user_id`,`user` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id` IN ('".join("','",$output['uids'])."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$output['users']='';
	while ($rsrow=mysql_fetch_assoc($res)) {
		$output['users'].='<a href="profile.php?user_id='.$rsrow['user_id'].'">'.$rsrow['user'].'</a>, ';
	}
	$output['users']=substr($output['users'],0,-2);
	$output['uids']=join('|',$output['uids']);
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No members selected';
	$nextpage=_BASEURL_.'/admin/member_search.php';
	if (!empty($output['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$output['return'];
	}
	redirect2page($nextpage,$topass,'',true);
}

$tpl->set_file('content','membership_assign.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Membership assignment';
include 'frame.php';
?>
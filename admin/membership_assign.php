<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/membership_assign.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (!empty($_REQUEST['search'])) {
	$output['search']=sanitize_and_format_gpc($_REQUEST,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search']."' AND `search_type`=".SEARCH_USER;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$output['uids']=explode(',',$results);
	}
} elseif (!empty($_REQUEST['uids'])) {
	$output['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
}
$output['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$output['return2']=rawurldecode($output['return']);

if (!empty($output['uids'])) {
	$output['m_value']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`',4,'`m_value`<>1');
	$output['users']='';
	if (count($output['uids'])<10) {
		$query="SELECT `".USER_ACCOUNT_ID."` as `user_id`,`".USER_ACCOUNT_USER."` as `user` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$output['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$output['users'].='<a href="profile.php?uid='.$rsrow['user_id'].'">'.$rsrow['user'].'</a>, ';
		}
		$output['users']=substr($output['users'],0,-2);
	} else {
		$output['users']='Selected members';
	}
	$output['uids']=join('|',$output['uids']);
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='No members selected';
	if (!empty($output['return'])) {
		$nextpage=_BASEURL_.'/admin/'.$output['return'];
	} else {
		$nextpage=_BASEURL_.'/admin/member_search.php';
	}
	redirect2page($nextpage,$topass,'',true);
}

if (empty($output['return'])) {
	if ($_SERVER['REQUEST_METHOD']=='GET') {
		// because of the GET, our 'return' is decoded
		$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		$output['return']=rawurlencode($output['return2']);
	} else {
		$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		$output['return2']=rawurldecode($output['return']);
	}
}

$tpl->set_file('content','membership_assign.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Membership assignment';
$tplvars['page']='membership_assign';
include 'frame.php';

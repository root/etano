<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/email_send.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} else {
	if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
		$output['search']=sanitize_and_format_gpc($_REQUEST,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search']."' AND `search_type`='".SEARCH_USER."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$results=mysql_result($res,0,0);
			$output['uids']=explode(',',$results);
		}
	} elseif (isset($_REQUEST['uids']) && !empty($_REQUEST['uids'])) {
		$output['uids']=sanitize_and_format($_REQUEST['uids'],TYPE_INT,0,array());
	}
	$output['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return2']=rawurldecode($output['return']);
}

if (!empty($output['uids'])) {
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

$tpl->set_file('content','email_send.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Send an Email';
$tplvars['page']='email_send';
$tplvars['css']='email_send.css';
include 'frame.php';
?>
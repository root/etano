<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/net_deluser.php
$Revision: 91 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
require_once '../includes/network_functions.inc.php';
check_login_member(-1);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='home.php';
$input=array();
// get the input we need and sanitize it
$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$input['net_id']=sanitize_and_format_gpc($_GET,'net_id',TYPE_INT,0,0);
if (isset($_GET['return']) && !empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD] | FORMAT_RUDECODE,'');
	$nextpage=$input['return'];
}

if (empty($input['uid'])) {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Invalid user';
}

if (empty($input['net_id'])) {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Invalid network selected';
}

if (!$error) {
	$query="SELECT `is_bidi` FROM `{$dbtable_prefix}networks` WHERE `net_id`='".$input['net_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$is_bidi=1;
	if (mysql_num_rows($res)) {
		$is_bidi=mysql_result($res,0,0);
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid network selected';
	}

	if (!$error) {
		$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE (`fk_user_id`='".$_SESSION['user']['user_id']."' AND `fk_net_id`='".$input['net_id']."' AND `fk_user_id_other`='".$input['uid']."')";
		if ($is_bidi) {
			$query.=" OR (`fk_user_id`='".$input['uid']."' AND `fk_net_id`='".$input['net_id']."' AND `fk_user_id_other`='".$_SESSION['user']['user_id']."')";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if ($input['net_id']==NET_BLOCK) {
			del_message_filter(array('filter_type'=>FILTER_SENDER,'fk_user_id'=>$_SESSION['user']['user_id'],'field_value'=>$input['uid']));
		}
		$topass['message']['type']=MESSAGE_INFO;
		if (!empty($is_bidi)) {
			$topass['message']['text']=sprintf('A confirmation request has been sent to %s',get_user_by_userid($input['uid']));     // translate
			$request['fk_user_id']=$input['uid'];
			$request['fk_user_id_other']=$_SESSION['user']['user_id'];
			$request['_user_other']=$_SESSION['user']['user'];
			$request['subject']=sprintf('Connection request from %s',$_SESSION['user']['user']);	// translate
			$request['message_body']=sprintf('%1s wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests',$_SESSION['user']['user']);
			$request['message_type']=MESS_SYSTEM;
			queue_or_send_message($input['uid'],$request);
		} else {
			$topass['message']['text']=sprintf('%1s has been added to your %2s',get_user_by_userid($input['uid']),get_net_name($input['net_id']));     // translate
		}
	}
}

if ($error) {
// 		you must re-read all textareas from $_GET like this:
//		$input['x']=addslashes_mq($_GET['x']);
	$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
	$topass['input']=$input;
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
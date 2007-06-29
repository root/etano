<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/net_adduser.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/network_functions.inc.php';
check_login_member('manage_networks');

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='home.php';
$input=array();
// get the input we need and sanitize it
$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$input['net_id']=sanitize_and_format_gpc($_GET,'net_id',TYPE_INT,0,0);
if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
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
		// if we already have a connect request from this member in this network simply join them
		$force_connect=0;
		if (!empty($is_bidi)) {
			$query="SELECT `nconn_id` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`='".$input['uid']."' AND `fk_net_id`='".$input['net_id']."' AND `fk_user_id_other`='".$_SESSION['user']['user_id']."' AND `nconn_status`=0";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$force_connect=mysql_result($res,0,0);
			}
		}
		$query="INSERT IGNORE INTO `{$dbtable_prefix}user_networks` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`fk_net_id`='".$input['net_id']."',`fk_user_id_other`='".$input['uid']."'";
		if (!empty($force_connect)) {
			$query.=",`nconn_status`=1";
		} else {
			$query.=",`nconn_status`='".(1-(int)$is_bidi)."'";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		// we added him to our network, let's add us to his network
		if (!empty($force_connect)) {
			$query="UPDATE `{$dbtable_prefix}user_networks` SET `nconn_status`=1 WHERE `nconn_id`='$force_connect'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		} elseif ($input['net_id']==NET_BLOCK) {
			add_message_filter(array('filter_type'=>FILTER_SENDER,'fk_user_id'=>$_SESSION['user']['user_id'],'field_value'=>$input['uid'],'fk_folder_id'=>FOLDER_SPAMBOX));
			add_member_score($input['uid'],'block_member');
		}
		$topass['message']['type']=MESSAGE_INFO;
		if (!empty($is_bidi) && empty($force_connect)) {
			$topass['message']['text']=sprintf('A confirmation request has been sent to %s',get_user_by_userid($input['uid']));     // translate
			$request['fk_user_id']=$input['uid'];
			$request['fk_user_id_other']=$_SESSION['user']['user_id'];
			$request['_user_other']=$_SESSION['user']['user'];
			$request['subject']=sprintf('Connection request from %s',$_SESSION['user']['user']);	// translate
			$request['message_body']=sprintf('%1$s wants to be your friend.<br><a class="content-link simple" href="friendship_requests.php">Click here</a> to see all friendship requests',$_SESSION['user']['user']);
			$request['message_type']=MESS_SYSTEM;
			queue_or_send_message($request);
		} else {
			$topass['message']['text']=sprintf('%1$s has been added to your %2$s',get_user_by_userid($input['uid']),get_net_name($input['net_id']));     // translate
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
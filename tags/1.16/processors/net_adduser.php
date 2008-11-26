<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/net_adduser.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/network_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/net.inc.php';
check_login_member('manage_networks');

if (is_file(_BASEPATH_.'/events/processors/net_adduser.php')) {
	include _BASEPATH_.'/events/processors/net_adduser.php';
}

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
	$topass['message']['text']=$GLOBALS['_lang'][81];
}

if (empty($input['net_id'])) {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][82];
}
$other_user_name=get_user_by_userid($input['uid']);
if (is_network_member($_SESSION[_LICENSE_KEY_]['user']['user_id'],$input['uid'],$input['net_id'])) {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=sprintf($GLOBALS['_lang'][280],$other_user_name);
}

if (!$error) {
	$query="SELECT `is_bidi` FROM `{$dbtable_prefix}networks` WHERE `net_id`=".$input['net_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$is_bidi=1;
	if (mysql_num_rows($res)) {
		$is_bidi=mysql_result($res,0,0);
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][82];
	}

	if (!$error) {
		// if we already have a connect request from this member in this network simply join them
		$force_connect=0;
		if (!empty($is_bidi)) {
			$query="SELECT `nconn_id` FROM `{$dbtable_prefix}user_networks` WHERE `fk_user_id`=".$input['uid']." AND `fk_net_id`=".$input['net_id']." AND `fk_user_id_other`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `nconn_status`=0";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$force_connect=mysql_result($res,0,0);
			}
		}
		if (isset($_on_before_insert)) {
			for ($i=0;isset($_on_before_insert[$i]);++$i) {
				call_user_func($_on_before_insert[$i]);
			}
		}
		$query="INSERT IGNORE INTO `{$dbtable_prefix}user_networks` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."',`fk_net_id`=".$input['net_id'].",`fk_user_id_other`=".$input['uid'];
		if (!empty($force_connect)) {
			$query.=",`nconn_status`=1";
		} else {
			$query.=",`nconn_status`=".(1-(int)$is_bidi);
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		// we added him to our network, let's add us to his network
		if (!empty($force_connect)) {
			$query="UPDATE `{$dbtable_prefix}user_networks` SET `nconn_status`=1 WHERE `nconn_id`=$force_connect";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		} elseif ($input['net_id']==NET_BLOCK) {
			add_message_filter(array('filter_type'=>FILTER_SENDER,'fk_user_id'=>$_SESSION[_LICENSE_KEY_]['user']['user_id'],'field_value'=>$input['uid'],'fk_folder_id'=>FOLDER_TRASH));
			add_member_score($input['uid'],'block_member');
		}
		$topass['message']['type']=MESSAGE_INFO;
		if (!empty($is_bidi) && empty($force_connect)) {
			$topass['message']['text']=sprintf($GLOBALS['_lang'][83],$other_user_name);
			$request['fk_user_id']=$input['uid'];
			$request['fk_user_id_other']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
			$request['_user_other']=sanitize_and_format($_SESSION[_LICENSE_KEY_]['user']['user'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
			$request['subject']=sanitize_and_format(sprintf($GLOBALS['_lang'][84],$_SESSION[_LICENSE_KEY_]['user']['user']),TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
			$request['message_body']=sanitize_and_format(sprintf($GLOBALS['_lang'][85],$_SESSION[_LICENSE_KEY_]['user']['user'],get_net_name($input['net_id'])),TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
			$request['message_type']=MESS_SYSTEM;
			queue_or_send_message($request);
		} else {
			$topass['message']['text']=sprintf($GLOBALS['_lang'][86],get_user_by_userid($input['uid']),get_net_name($input['net_id']));
		}
		if (isset($_on_after_insert)) {
			for ($i=0;isset($_on_after_insert[$i]);++$i) {
				call_user_func($_on_after_insert[$i]);
			}
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

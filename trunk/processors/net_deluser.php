<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/net_deluser.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/network_functions.inc.php';
check_login_member('manage_networks');

if (is_file(_BASEPATH_.'/events/processors/net_deluser.php')) {
	include_once _BASEPATH_.'/events/processors/net_deluser.php';
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
	$topass['message']['text']='Invalid user';
}

if (empty($input['net_id'])) {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Invalid network selected';
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
		$topass['message']['text']='Invalid network selected';
	}

	if (!$error) {
		$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE (`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_net_id`=".$input['net_id']." AND `fk_user_id_other`=".$input['uid'].")";
		if ($is_bidi) {
			$query.=" OR (`fk_user_id`=".$input['uid']." AND `fk_net_id`=".$input['net_id']." AND `fk_user_id_other`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."')";
		}
		if (isset($_on_before_delete)) {
			for ($i=0;isset($_on_before_delete[$i]);++$i) {
				call_user_func($_on_before_delete[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (isset($_on_after_delete)) {
			for ($i=0;isset($_on_after_delete[$i]);++$i) {
				call_user_func($_on_after_delete[$i]);
			}
		}
		if ($input['net_id']==NET_BLOCK) {
			del_message_filter(array('filter_type'=>FILTER_SENDER,'fk_user_id'=>$_SESSION[_LICENSE_KEY_]['user']['user_id'],'field_value'=>$input['uid']));
			add_member_score($input['uid'],'unblock_member');
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('%1$s has been removed from your %2$s',get_user_by_userid($input['uid']),get_net_name($input['net_id']));     // translate
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

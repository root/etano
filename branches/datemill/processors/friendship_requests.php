<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/friendship_requests.php
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
check_login_member('manage_networks');

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='friendship_requests.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['nconn_id']=sanitize_and_format_gpc($_POST,'nconn_id',TYPE_INT,0,array());
	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		if (!empty($input['nconn_id'])) {
			if (isset($_POST['btn_accept'])) {
				$query="UPDATE `{$dbtable_prefix}user_networks` SET `nconn_status`=1 WHERE `nconn_id` IN ('".join("','",$input['nconn_id'])."')";
				if (isset($_on_before_insert)) {
					for ($i=0;isset($_on_before_insert[$i]);++$i) {
						eval($_on_before_insert[$i].'();');
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query="SELECT `fk_user_id`,`fk_net_id` FROM `{$dbtable_prefix}user_networks` WHERE `nconn_id` IN ('".join("','",$input['nconn_id'])."')";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$new_friends=0;
				while ($rsrow=mysql_fetch_assoc($res)) {
					$query="INSERT IGNORE INTO `{$dbtable_prefix}user_networks` SET `fk_user_id`='".$_SESSION['user']['user_id']."',`fk_net_id`=".$rsrow['fk_net_id'].",`fk_user_id_other`=".$rsrow['fk_user_id'].",`nconn_status`=1";
					if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					++$new_friends;
					update_stats($rsrow['fk_user_id'],'num_friends',1);
				}
				if (!empty($new_friends)) {
					update_stats($_SESSION['user']['user_id'],'num_friends',$new_friends);
				}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']=sprintf('%s connections added',count($input['nconn_id']));     // translate
				if (isset($_on_after_insert)) {
					for ($i=0;isset($_on_after_insert[$i]);++$i) {
						eval($_on_after_insert[$i].'();');
					}
				}
			} elseif (isset($_POST['btn_deny'])) {
				$query="DELETE FROM `{$dbtable_prefix}user_networks` WHERE `nconn_id` IN ('".join("','",$input['nconn_id'])."')";
				if (isset($_on_before_delete)) {
					for ($i=0;isset($_on_before_delete[$i]);++$i) {
						eval($_on_before_delete[$i].'();');
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']=sprintf('%s connections declined',count($input['nconn_id']));     // translate
				if (isset($_on_after_delete)) {
					for ($i=0;isset($_on_after_delete[$i]);++$i) {
						eval($_on_after_delete[$i].'();');
					}
				}
			}
		}
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				eval($_on_error[$i].'();');
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

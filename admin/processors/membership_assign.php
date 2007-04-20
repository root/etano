<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/membership_assign.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$query_strlen=10000;
$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['uids']=isset($_POST['uids']) ? $_POST['uids'] : '';
	$input['uids']=explode('|',$input['uids']);
	$input['uids']=sanitize_and_format($input['uids'],TYPE_INT,0,array());
	$input['m_value']=sanitize_and_format_gpc($_POST,'m_value',TYPE_INT,0,0);
	$input['duration']=sanitize_and_format_gpc($_POST,'duration',TYPE_INT,0,0);
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD] | FORMAT_RUDECODE,'');

	if (empty($input['duration'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the duration of this membership';
	}

	if (!$error) {
		$query="SELECT `user_id`,`user`,`membership` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id` IN ('".join("','",$input['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$uids=array();
		$insert="INSERT INTO `{$dbtable_prefix}payments` (`fk_user_id`,`_user`,`gateway`,`m_value_from`,`m_value_to`,`paid_from`,`paid_until`) VALUES ";
		$query=$insert;
		while ($rsrow=mysql_fetch_assoc($res)) {
			if (strlen($query)>$query_strlen) {
				$query=substr($query,0,-1);
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query=$insert;
			}
			$query.="('".$rsrow['user_id']."','".$rsrow['user']."','manual','".$rsrow['membership']."','".$input['m_value']."',now(),now()+INTERVAL '".$input['duration']."' DAY),";
		}
		if ($query!=$insert) {
			$query=substr($query,0,-1);
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$query="UPDATE ".USER_ACCOUNTS_TABLE." SET `membership`='".$input['m_value']."' WHERE `user_id` IN ('".join("','",$input['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('Membership assigned to %1$s members for %2$s days',count($input['uids']),$input['duration']);
	}
}
$nextpage=_BASEURL_.'/admin/member_search.php';
if (isset($input['return']) && !empty($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
}
redirect2page($nextpage,$topass,$qs,true);
?>
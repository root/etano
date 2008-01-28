<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/membership_assign.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
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
	$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	if (!$error) {
		$now=gmdate('YmdHis');
		$query="UPDATE `{$dbtable_prefix}payments` SET `paid_until`='$now',`is_active`=0 WHERE `fk_user_id` IN ('".join("','",$input['uids'])."') AND `is_active`=1 AND `is_subscr`=1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="SELECT `".USER_ACCOUNT_ID."` as `user_id`,`".USER_ACCOUNT_USER."` as `user`,`membership` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$input['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$uids=array();
		$insert="INSERT INTO `{$dbtable_prefix}payments` (`is_active`,`is_subscr`,`fk_user_id`,`_user`,`gateway`,`m_value_to`,`paid_from`,`paid_until`,`date`) VALUES ";
		$query=$insert;
		while ($rsrow=mysql_fetch_assoc($res)) {
			if (strlen($query)>$query_strlen) {
				$query=substr($query,0,-1);
				if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$query=$insert;
			}
			$query.="(1,1,'".$rsrow['user_id']."','".$rsrow['user']."','manual','".$input['m_value']."','$now'";
			if (!empty($input['duration'])) {
				$query.=",'$now'+INTERVAL '".$input['duration']."' DAY";
			} else {
				$query.=",'0000-00-00'"
			}
			$query.=",now()),";
		}
		if ($query!=$insert) {
			$query=substr($query,0,-1);
			if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
		$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `membership`='".$input['m_value']."' WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$input['uids'])."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=sprintf('Membership assigned to %1$s members for %2$s days',count($input['uids']),$input['duration']);
	}
}
$nextpage=_BASEURL_.'/admin/member_search.php';
if (!empty($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
}
redirect2page($nextpage,$topass,$qs,true);

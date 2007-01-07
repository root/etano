<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/login.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$topass=array();
$qs='';
$qs_sep='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$user=sanitize_and_format($_POST['username'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	$pass=sanitize_and_format($_POST['password'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');
	if (isset($user) && !empty($user) && isset($pass) && !empty($pass)) {
		$query="SELECT `admin_id`,`name`,`dept_id`,`status` FROM `{$dbtable_prefix}admin_accounts` WHERE `user`='$user' AND `pass`=md5('$pass')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$admin=mysql_fetch_assoc($res);
			if ($admin['status']==_ASTAT_ACTIVE_) {
				$_SESSION['admin']=$admin;
				if (isset($_SESSION['admin']['timedout']['url'])) {
					$page=$_SESSION['admin']['timedout']['url'];
					$qs=isset($_SESSION['admin']['timedout']['qs']) ? $_SESSION['admin']['timedout']['qs'] : array();
					if ($_SESSION['timedout']['method']=='GET') {
						unset($_SESSION['admin']['timedout']);
						if (!empty($qs)) {
							$page=$page.'?'.array2qs($qs);
						}
						redirect2page($page,array(),'',true);
					} else {
						unset($_SESSION['admin']['timedout']);
						post2page($page,$qs,true);
					}
				} else {
					redirect2page('admin/cpanel.php',$topass);
				}
			} else {
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Your account has been suspended';
			}
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid user or pass. Please try again!';
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid user or pass. Please try again!';
	}
}
redirect2page('admin/index.php',$topass);
?>
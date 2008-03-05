<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/login.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';

$topass=array();
$qs='';
$qs_sep='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$username=strtolower(sanitize_and_format_gpc($_POST,'username',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
	$password=sanitize_and_format_gpc($_POST,'password',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (!empty($username) && !empty($password)) {
		$query="SELECT `admin_id`,`name`,`dept_id`,`status` FROM `{$dbtable_prefix}admin_accounts` WHERE `user`='$username' AND `pass`=md5('$password')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$admin=mysql_fetch_assoc($res);
			if ($admin['status']==ASTAT_ACTIVE) {
				$_SESSION[_LICENSE_KEY_]['admin']=array_merge(isset($_SESSION[_LICENSE_KEY_]['admin']) ? $_SESSION[_LICENSE_KEY_]['admin'] : array(),$admin);
				$_SESSION[_LICENSE_KEY_]['admin']['def_skin']=get_default_skin_dir();
				if (isset($_SESSION[_LICENSE_KEY_]['admin']['timedout']['url'])) {
					$next=$_SESSION[_LICENSE_KEY_]['admin']['timedout'];
					unset($_SESSION[_LICENSE_KEY_]['admin']['timedout']);
					if ($next['method']=='GET') {
						if (!empty($next['qs'])) {
							$next['url']=$next['url'].'?'.array2qs($next['qs']);
						}
						redirect2page($next['url'],array(),'',true);
					} else {
						post2page($next['url'],$next['qs'],true);
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
			$topass['message']['text']='Invalid username or pass. Please try again!';
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid username or pass. Please try again!';
	}
}
redirect2page('admin/index.php',$topass);

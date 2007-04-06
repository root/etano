<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/manage_memberships.php
$Revision$
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	if (isset($_POST['act'])) {
		if ($_POST['act']=='add') {
			$input['m_name']=sanitize_and_format_gpc($_POST,'m_name',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

		// check for input errors
			if (empty($input['m_name'])) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='The membership name cannot be empty! Please enter a name.';
			}
			$query="SELECT `m_id` FROM `{$dbtable_prefix}memberships` WHERE `m_name`='".$input['m_name']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='This membership already exists! Please enter a unique name.';
			}
			$query="SELECT `m_value` FROM `{$dbtable_prefix}memberships`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$m_values=array();
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$m_values[]=mysql_result($res,$i,0);
			}
			if (count($m_values)==10) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='You cannot add more than 10 memberships.';
			}

			if (!$error) {
				for ($i=0;$i<10;++$i) {
					$temp=pow(2,$i);
					if (!in_array($temp,$m_values)) {
						$input['m_value']=$temp;
						break;
					}
				}
				if (!empty($input['m_value'])) {
					$query="INSERT INTO `{$dbtable_prefix}memberships` SET `m_name`='".$input['m_name']."',`m_value`='".$input['m_value']."',is_custom=1";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$topass['message']['type']=MESSAGE_INFO;
					$topass['message']['text']='Membership added.';
				}
			}
		} elseif ($_POST['act']=='del') {
			$input['m_id']=(int)$_POST['m_id'];
			$query="SELECT `m_value` FROM `{$dbtable_prefix}memberships` WHERE `m_id`='".$input['m_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$m_value=(int)mysql_result($res,0,0);
				$query="DELETE FROM `{$dbtable_prefix}memberships` WHERE `m_id`='".$input['m_id']."'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

				$query="SELECT `level_id`,`level` FROM `{$dbtable_prefix}access_levels`";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				while ($rsrow=mysql_fetch_row($res)) {
					if (((int)$rsrow[1]) & $m_value) {
						$query="UPDATE `{$dbtable_prefix}access_levels` SET `level`='".($rsrow[1]-$m_value)."' WHERE `level_id`='".$rsrow[0]."'";
						if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					}
				}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Membership deleted.';
			}
// we should regenerate all access levels here to remove the just deleted membership.
		}
	}
}
redirect2page('admin/access_levels.php',$topass,$qs);
?>
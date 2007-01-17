<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/rate_limiter_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/rate_limiter.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$rate_limiter=$rate_limiter_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$rate_limiter=$_SESSION['topass']['input'];
} elseif (isset($_GET['rate_id']) && !empty($_GET['rate_id'])) {
	$rate_id=(int)$_GET['rate_id'];
	$query="SELECT * FROM `{$dbtable_prefix}rate_limiter` WHERE `rate_id`='$rate_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rate_limiter=mysql_fetch_assoc($res);
		$rate_limiter=sanitize_and_format($rate_limiter,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	}
}
$rate_limiter['m_value']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`',$rate_limiter['m_value']);
$rate_limiter['fk_level_id']=dbtable2options("`{$dbtable_prefix}access_levels`",'`level_id`','`level_code`','`level_id`',$rate_limiter['fk_level_id']);
$rate_limiter['punishment']=vector2options($accepted_punishments,$rate_limiter['punishment']);

$tpl->set_file('content','rate_limiter_addedit.html');
$tpl->set_var('rate_limiter',$rate_limiter);
$tpl->process('content','content');

$tplvars['title']='Limits Management';
include 'frame.php';
?>
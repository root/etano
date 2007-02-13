<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/rate_limiter.php
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
require_once '../includes/logs.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','rate_limiter.html');

$query="SELECT a.*,b.`m_name`,c.`level_code` FROM `{$dbtable_prefix}rate_limiter` a,`{$dbtable_prefix}memberships` b,`{$dbtable_prefix}access_levels` c WHERE a.`m_value`=b.`m_value` AND a.`fk_level_id`=c.`level_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=0;
$rate_limiter=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['punishment']=isset($accepted_punishments[$rsrow['punishment']]) ? $accepted_punishments[$rsrow['punishment']] : '?';
	$rate_limiter[]=$rsrow;
}
$rate_limiter=sanitize_and_format($rate_limiter,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

$tpl->set_loop('rate_limiter',$rate_limiter);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('rate_limiter');

$tplvars['title']='Rate Limiter';
include 'frame.php';
?>
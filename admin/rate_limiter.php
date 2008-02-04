<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/rate_limiter.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/logs.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','rate_limiter.html');

$query="SELECT a.*,b.`m_name` FROM `{$dbtable_prefix}rate_limiter` a,`{$dbtable_prefix}memberships` b WHERE a.`m_value`=b.`m_value`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=0;
$rate_limiter=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['punishment']=isset($accepted_punishments[$rsrow['punishment']]) ? $accepted_punishments[$rsrow['punishment']] : '?';
	$rate_limiter[]=$rsrow;
}
$rate_limiter=sanitize_and_format($rate_limiter,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$tpl->set_loop('rate_limiter',$rate_limiter);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('rate_limiter');

$tplvars['title']='Rate Limiter';
$tplvars['page']='rate_limiter';
include 'frame.php';

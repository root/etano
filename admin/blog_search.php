<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/blog_search.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$profile=array();
$profile['pstat']=vector2options($accepted_pstats);

$tpl->set_file('content','blog_search.html');
$tpl->set_var('profile',$profile);

$tpl->process('content','content',TPL_LOOP);

$tplvars['title']='Search';
include 'frame.php';
?>
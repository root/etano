<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/cpanel.php
$Revision$
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
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','cpanel.html');
$tpl->set_var('date',strftime("%c"));
$tpl->process('content','content');

$tplvars['title']='Your admin control panel';
include 'frame.php';
?>
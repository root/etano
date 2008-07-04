<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/db_backup.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/admin_accounts.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','db_backup.html');
$tpl->process('content','content');

$tplvars['title']='Backup Database';
$tplvars['page']='db_backup';
$tplvars['css']='db_backup.css';
include 'frame.php';

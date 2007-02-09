<?php
/******************************************************************************
newdsb
===============================================================================
File:                       login.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$tpl->set_file('content','login.html');
$tpl->process('content','content');

$tplvars['title']='Login';
$tplvars['page_title']='Login to continue...';
$tplvars['page']='login';
$tplvars['css']='login.css';
include 'frame.php';
?>
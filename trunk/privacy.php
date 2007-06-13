<?php
/******************************************************************************
newdsb
===============================================================================
File:                       privacy.php
$Revision: 91 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','privacy.html');
$tpl->process('content','content');

$tplvars['title']='Privacy';
$tplvars['page_title']='Privacy';
$tplvars['page']='privacy';
$tplvars['css']='privacy.css';
include 'frame.php';
?>
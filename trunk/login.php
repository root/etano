<?php
/******************************************************************************
Etano
===============================================================================
File:                       login.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','login.html');
$tpl->process('content','content');

$tplvars['title']='Login';
$tplvars['page_title']='Login to continue...';
$tplvars['page']='login';
$tplvars['css']='login.css';
include 'frame.php';
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       tos.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','tos.html');
$tpl->process('content','content');

$tplvars['title']='Terms of Service';
$tplvars['page_title']='Terms of Service';
$tplvars['page']='tos';
$tplvars['css']='tos.css';
include 'frame.php';
?>
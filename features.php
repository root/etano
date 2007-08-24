<?php
/******************************************************************************
Etano
===============================================================================
File:                       features.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','features.html');
$tpl->process('content','content');

$tplvars['title']='Features';
$tplvars['menu_features']='active';
$tplvars['page_title']='Features';
$tplvars['page']='features';
$tplvars['css']='features.css';
include 'frame.php';

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
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/tos.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','tos.html');
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][250];
$tplvars['page_title']=$GLOBALS['_lang'][250];
$tplvars['page']='tos';
$tplvars['css']='tos.css';
include 'frame.php';

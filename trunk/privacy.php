<?php
/******************************************************************************
Etano
===============================================================================
File:                       privacy.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/privacy.php';

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$tpl->set_file('content','privacy.html');
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][246];
$tplvars['page_title']=$GLOBALS['_lang'][246];
$tplvars['page']='privacy';
$tplvars['css']='privacy.css';
$no_timeout=true;
include 'frame.php';

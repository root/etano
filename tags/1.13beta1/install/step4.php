<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/step4.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

ini_set('include_path','.');
ini_set('session.use_cookies',1);
ini_set('session.use_trans_sid',0);
ini_set('date.timezone','GMT');	// temporary fix for the php 5.1+ TZ compatibility
ini_set('error_reporting',2047);
ini_set('display_errors',0);
define('_LICENSE_KEY_','');
require_once '../includes/sessions.inc.php';
require_once '../includes/sco_functions.inc.php';
require_once '../includes/classes/phemplate.class.php';

$output=array();
$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','step4.html');

$tplvars=array();
$tplvars['page_title']='Etano Install Process';
$tplvars['css']='step4.css';
$tplvars['page']='step4';
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content');
include 'frame.php';

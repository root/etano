<?php
/******************************************************************************
Etano
===============================================================================
File:                       upgrade.php
$Revision: 320 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

// upgrade request from DSB to Etano.

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} else {
	$output['old_url']='http://';
}
$tpl->set_file('content','upgrade.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Free upgrade to Etano community builder';
$tplvars['page_title']='Upgrade Request: Step 1';
$tplvars['page']='upgrade';
$tplvars['css']='upgrade.css';
include 'frame.php';

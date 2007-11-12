<?php
/******************************************************************************
Etano
===============================================================================
File:                       upgrade_verif.php
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
if (isset($_GET['upid'])) {
	$output['upid']=(int)$_GET['upid'];
	if (isset($_SESSION[_LICENSE_KEY_]['user']['upkey'])) {
		$output['upkey']=$_SESSION[_LICENSE_KEY_]['user']['upkey'];
		$output['upsite']=$_SESSION[_LICENSE_KEY_]['user']['upsite'];
	} else {
		$query="SELECT `key`,`old_url` FROM `dsb2_upgrades` WHERE `upid`=".$output['upid'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output['upkey']=mysql_result($res,0,0);
			$output['upsite']=mysql_result($res,0,1);
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please enter your DSB purchase details first.';
			redirect2page('upgrade.php');
		}
	}
	$tpl->set_file('content','upgrade_verif.html');
	$tpl->set_var('output',$output);
	$tpl->process('content','content');
}

$tplvars['title']='Free upgrade to Etano community builder';
$tplvars['page_title']='Upgrade Request: Step 2';
$tplvars['page']='upgrade_verif';
$tplvars['css']='upgrade_verif.css';
include 'frame.php';

<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/step2.php
$Revision: 193 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/sco_functions.inc.php';
require_once '../includes/classes/phemplate.class.php';

$output=array();
$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','step2.html');

$error=false;
$topass=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['dbtable_prefix']='dsb_';
} elseif (isset($_SESSION['install']['input'])) {
	$output=$_SESSION['install']['input'];
	$output['dbtable_prefix']='dsb_';
} else {
	$my_url=str_replace('/install/step2.php','',$_SERVER['PHP_SELF']);
	$output['baseurl']=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$my_url;

	if (!empty($_SERVER['PATH_TRANSLATED'])) {
		$output['basepath']=$_SERVER['PATH_TRANSLATED'];
	} elseif (!empty($_SERVER['SCRIPT_FILENAME'])) {
		$output['basepath']=$_SERVER['SCRIPT_FILENAME'];
	}

	if ($output['basepath']{1}==':') {
		$output['basepath']=substr($output['basepath'],2);
		$output['basepath']=str_replace('\\\\','\\',$output['basepath']);
		$output['basepath']=str_replace('\\','/',$output['basepath']);
	}
	$output['basepath']=str_replace('/install/step2.php','',$output['basepath']);

	$output['dbhost']='localhost';
	$output['ftphost']='localhost';
	$output['dbtable_prefix']='dsb_';

	if (!empty($_SESSION['install']['write']) && $_SESSION['install']['write']==2) {
		$temp=fileowner(__FILE__);
		if (!empty($temp)) {
			if (function_exists('posix_getpwuid')) {
				$temp=posix_getpwuid($temp);
				$output['ftpuser']=$temp['name'];
				$output['ftppath']=$temp['dir'];
				if (strpos($output['basepath'],$output['ftppath'])===0) {
					$output['ftppath']=str_replace($output['ftppath'],'',$output['basepath'].'/');
				}
			}
		}
	}
}

if (!empty($_SESSION['install']['write']) && $_SESSION['install']['write']==2) {
	$output['has_ftp']=true;
}

$tplvars=array();
$tplvars['page_title']='Etano Install Process';
$tplvars['css']='step2.css';
$tplvars['page']='step2';
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL);
include 'frame.php';

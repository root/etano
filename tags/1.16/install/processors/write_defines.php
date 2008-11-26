<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/processors/write_defines.php
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
require_once '../../includes/sessions.inc.php';
require_once '../../includes/sco_functions.inc.php';
require_once '../../includes/classes/phemplate.class.php';

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='install/step3.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$accepted_inputs=array('site_name','baseurl','basepath','dbhost','dbuser','dbpass','dbname','dbtable_prefix','ftphost','ftpuser','ftppass','ftppath','license_key');
	$input=array();
// get the input we need and sanitize it
	for ($i=0;isset($accepted_inputs[$i]);++$i) {
		$input[$accepted_inputs[$i]]=sanitize_and_format_gpc($_POST,$accepted_inputs[$i],TYPE_STRING,FORMAT_STRIP_MQ | FORMAT_OLD_ADDSLASH | FORMAT_ONELINE | FORMAT_TRIM,'');
	}
	$input['dbtable_prefix']='dsb_';

// check for input errors
	if (empty($input['site_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Site Name cannot be left empty';
	}
	if (empty($input['baseurl'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Base URL cannot be left empty';
	}
	if (empty($input['basepath'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Base Path cannot be left empty';
	}
	if (!is_dir($input['basepath'].'/media/pics/t1/0')) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Base Path is wrong or not all files were uploaded';
	}
	if (empty($input['dbhost'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Database Server Host/IP cannot be left empty';
	}
	if (empty($input['dbuser'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Database User cannot be left empty';
	}
	if (empty($input['dbpass'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Database Password cannot be left empty';
	}
	if (empty($input['dbname'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The Database Name cannot be left empty';
	}
	if (function_exists('mysql_connect')) {
		$link=mysql_connect($input['dbhost'],$input['dbuser'],$input['dbpass']);
		if ($link) {
			if (!@mysql_select_db($input['dbname'],$link)) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]='Database Host, user and password are ok but the database name is wrong.';
			}
			mysql_close($link);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='Database Host or user or password are wrong.';
		}
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Server configuration does not allow db connections.';
	}
	if (!empty($_SESSION['install']['write']) && $_SESSION['install']['write']=='ftp') {
		if (empty($input['ftphost'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='The FTP Server Host/IP cannot be left empty';
		}
		if (empty($input['ftpuser'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='The FTP User cannot be left empty';
		}
		if (empty($input['ftppass'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='The FTP Password cannot be left empty';
		}
		if (empty($input['ftppath'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='The FTP Path cannot be left empty';
		}
		if (function_exists('ftp_connect')) {
			$link=@ftp_connect($input['ftphost'],21,10);
			if ($link) {
				if (@ftp_login($link,$input['ftpuser'],$input['ftppass'])) {
					if (!@ftp_chdir($link,$input['ftppath'].'media/pics/t1/0')) {	// a pretty unique path
						$error=true;
						$topass['message']['type']=MESSAGE_ERROR;
						$topass['message']['text'][]='The connection was successful but the FTP Path is wrong.';
					}
				} else {
					$error=true;
					$topass['message']['type']=MESSAGE_ERROR;
					$topass['message']['text'][]='FTP Host is ok but either the user or the password are wrong.';
				}
				ftp_close($link);
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]='FTP Host is wrong.';
			}
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]='Server configuration does not allow ftp connections.';
		}
	}
	if (empty($input['license_key']) || strlen($input['license_key'])!=22) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='The license key is invalid';
	}

	if (!$error) {
		$input['fileop_mode']=$_SESSION['install']['write'];
		$input['license_key_md5']=md5($input['license_key']);
		$tpl=new phemplate('../skin/','remove_nonjs');
		$tpl->set_file('content','defines.inc.php');
		$tpl->set_var('input',$input);
		$towrite=$tpl->process('content','content',TPL_FINISH);

		define('_BASEPATH_',$input['basepath']);
		define('_FILEOP_MODE_',$input['fileop_mode']);
		define('_FTPHOST_',$input['ftphost']);
		define('_FTPPATH_',$input['ftppath']);
		define('_FTPUSER_',$input['ftpuser']);
		define('_FTPPASS_',$input['ftppass']);
		require_once '../../includes/classes/fileop.class.php';
		$fileop=new fileop();
		$fileop->delete($input['basepath'].'/includes/defines.inc.php');
		$fileop->file_put_contents($input['basepath'].'/includes/defines.inc.php',$towrite);
		$_SESSION['install']['input']=$input;
	} else {
		$nextpage='install/step2.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$my_url=str_replace('/install/processors/write_defines.php','',$_SERVER['PHP_SELF']);
define('_BASEURL_',((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$my_url);
redirect2page($nextpage,$topass,$qs);

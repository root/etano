<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/ajax/check_ftp.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

define('_LICENSE_KEY_','');
require_once dirname(__FILE__).'/../../includes/sessions.inc.php';
require_once dirname(__FILE__).'/../../includes/sco_functions.inc.php';

$output='';
if (!empty($_POST['ftphost']) && !empty($_POST['ftpuser']) && !empty($_POST['ftppass']) && !empty($_POST['ftppath'])) {
	$host=addslashes_mq($_POST['ftphost']);
	$user=addslashes_mq($_POST['ftpuser']);
	$pass=addslashes_mq($_POST['ftppass']);
	$path=addslashes_mq($_POST['ftppath']);
	if (function_exists('ftp_connect')) {
		$link=@ftp_connect($host,21,10);
		if ($link) {
			if (@ftp_login($link,$user,$pass)) {
				if (@ftp_chdir($link,$path.'media/pics/t1/0')) {	// a pretty unique path
					$output='Connection successfull. The FTP Path is ok.';
				} else {
					$output='The connection was successfull but the FTP Path is wrong.';
				}
			} else {
				$output='FTP Host is ok but either the user or the password are wrong.';
			}
			ftp_close($link);
		} else {
			$output='FTP Host is wrong.';
		}
	} else {
		$output='Server configuration does not allow ftp connections.';
	}
} else {
	$output='You must fill in all parameters (FTP host, user, password)';
}
echo $output;

<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/processors/insert_db.php
$Revision: 192 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/sco_functions.inc.php';
set_time_limit(0);

$error=false;
$topass=array();
$nextpage='install/step4.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	if (isset($_SESSION['install']['input'])) {
		if (function_exists('mysql_connect')) {
			$link=mysql_connect($input['dbhost'],$input['dbuser'],$input['dbpass']);
			if ($link) {
				if (!@mysql_select_db($input['dbname'],$link)) {
					$error=true;
					$topass['message']['type']=MESSAGE_ERROR;
					$topass['message']['text'][]='Database Host, user and password are ok but the database name is wrong.';
					mysql_close($link);
				}
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
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text'][]='Server configuration does not allow db connections.';
	}
}
$my_url=str_replace('/install/processors/insert_db.php','',$_SERVER['PHP_SELF']);
define('_BASEURL_',((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$my_url);
redirect2page($nextpage,$topass,$qs);
?>
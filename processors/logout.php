<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/logout.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';

if (is_file(_BASEPATH_.'/events/processors/logout.php')) {
	include_once _BASEPATH_.'/events/processors/logout.php';
}

if (isset($_SESSION['user']['user_id'])) {
	$query="DELETE FROM `{$dbtable_prefix}online` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (isset($_on_before_insert)) {
		for ($i=0;isset($_on_before_insert[$i]);++$i) {
			eval($_on_before_insert[$i].'();');
		}
	}
	@mysql_query($query);
	add_member_score($_SESSION['user']['user_id'],'logout');
	if (isset($_on_after_insert)) {
		for ($i=0;isset($_on_after_insert[$i]);++$i) {
			eval($_on_after_insert[$i].'();');
		}
	}
}

$_SESSION['user']=array();
$_SESSION['user']['loginout']=time();
unset($_SESSION['user']);
header('Expires: Mon,26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '. gmdate('D,d M Y H:i:s').' GMT');
header('Cache-Control: no-store,no-cache,must-revalidate',false);
header('Cache-Control: post-check=0,pre-check=0',false);
header('Pragma: no-cache',false);
redirect2page('index.php');

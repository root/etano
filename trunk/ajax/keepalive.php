<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/keepalive.php
$Revision: 186 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';

if (isset($_SESSION['user']['user_id'])) {
	check_login_member('all');
}
?>
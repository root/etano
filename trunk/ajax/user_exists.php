<?php
/******************************************************************************
newdsb
===============================================================================
File:                       ajax/user_exists.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/user_functions.inc.php';
require_once dirname(__FILE__).'/../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$output='';
if (isset($_POST['user']) && !empty($_POST['user'])) {
	$user=sanitize_and_format($_POST['user'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	if (get_userid_by_user($user)) {
		$output=1;
	}
}
echo $output;
?>
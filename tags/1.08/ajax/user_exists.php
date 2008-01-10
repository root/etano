<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/user_exists.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';

$output='';
if (!empty($_POST['user'])) {
	$user=sanitize_and_format($_POST['user'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	if (get_userid_by_user($user) || $user=='guest') {
		$output=1;
	}
}
echo $output;

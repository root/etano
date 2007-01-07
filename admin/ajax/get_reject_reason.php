<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/ajax/file_browser.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../../includes/sessions.inc.php';
require_once dirname(__FILE__).'/../../includes/classes/phemplate.class.php';
require_once dirname(__FILE__).'/../../includes/vars.inc.php';
require_once dirname(__FILE__).'/../../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$amtpl_id=sanitize_and_format_gpc($_POST,'amtpl_id',TYPE_INT,0,0);
$output='';

$query="SELECT `subject`,`message_body` FROM `{$dbtable_prefix}admin_mtpls` WHERE `amtpl_id`='$amtpl_id'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output.='<reason_title>'.rawurlencode(mysql_result($res,0,0)).'</reason_title>';
	$output.='<reject_reason>'.rawurlencode(mysql_result($res,0,1)).'</reject_reason>';
}

header('Content-type: text/xml');
echo '<result>'.$output.'</result>';
?>
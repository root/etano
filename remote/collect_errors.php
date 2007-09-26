<?php
/******************************************************************************
datemill.com
===============================================================================
File:                       remote/collect_errors.php
$Revision: 193 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['id']=sanitize_and_format_gpc($_POST,'id',TYPE_INT,0,0);
	$input['error']=sanitize_and_format_gpc($_POST,'e',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	$input['m']=sanitize_and_format_gpc($_POST,'m',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	if (!empty($input['id']) && !empty($input['error'])) {
		$query="INSERT INTO `customer_errors` (`fk_csite_id`,`module`,`error`) VALUES (".$input['id'].",'".$input['m']."','".$input['error']."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/products_delete.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/classes/fileop.class.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$prod_id=isset($_GET['prod_id']) ? (int)$_GET['prod_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$query="SELECT `filename` FROM `products` WHERE `prod_id`=$prod_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$fileop=new fileop();
	$fileop->delete(_BASEPATH_.'/dafilez/products/'.mysql_result($res,0,0));

	$query="DELETE FROM `products` WHERE `prod_id`=$prod_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Product deleted.';
} else {
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Product not found.';
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/products.php';
}
redirect2page($nextpage,$topass,'',true);

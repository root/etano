<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_news_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/site_news.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$site_news_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} elseif (!empty($_GET['news_id'])) {
	$news_id=(int)$_GET['news_id'];
	$query="SELECT * FROM `{$dbtable_prefix}site_news` WHERE `news_id`='$news_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['news_title']=sanitize_and_format($output['news_title'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		$output['news_body']=sanitize_and_format($output['news_body'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$output['return']=rawurlencode($output['return2']);

$tpl->set_file('content','site_news_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Site News Management';
$tplvars['css']='site_news_addedit.css';
$tplvars['page']='site_news_addedit';
include 'frame.php';
?>
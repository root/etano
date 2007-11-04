<?php
/******************************************************************************
Etano
===============================================================================
File:                       site_edit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$output['is_featured']=0;
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} elseif (!empty($_GET['site_id'])) {
	$site_id=(int)$_GET['site_id'];
	$query="SELECT `site_id`,`baseurl`,`remote_cron`,`is_featured`,`screenshot`,`license` FROM `user_sites` WHERE `site_id`=$site_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

if (empty($output['baseurl'])) {
	$output['baseurl']='http://';
}
if (!empty($output['is_featured'])) {
	$output['is_featured']='checked="checked"';
}
if (!empty($output['remote_cron'])) {
	$output['remote_cron']='checked="checked"';
}
if (!empty($output['screenshot'])) {
	$output['screenshot']='<img src="'._PHOTOURL_.'/sites/'.$output['screenshot'].'" alt="" />';
}
if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUENCODE,'');
}

$tpl->set_file('content','site_edit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Etano - site details';
$tplvars['page_title']='License Details';
$tplvars['page']='site_edit';
$tplvars['css']='site_edit.css';
if (is_file('site_edit_left.php')) {
	include 'site_edit_left.php';
}
include 'frame.php';

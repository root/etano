<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_sites_addedit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/user_sites.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$user_sites_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['site_id'])) {
	$site_id=(int)$_GET['site_id'];
	$query="SELECT `site_id`,`fk_user_id`,`baseurl`,`ftp_user`,`ftp_pass`,`active`,`license`,`license_md5`,`remote_cron`,`is_featured`,`screenshot` FROM `user_sites` WHERE `site_id`=$site_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
} elseif (!empty($_GET['uid'])) {
	$output['fk_user_id']=(int)$_GET['uid'];
} else {
	redirect2page('admin/cpanel.php');
}

$output['user']=db_key2value('`dsb_user_profiles`','`fk_user_id`','`_user`',$output['fk_user_id']);
$output['active']=!empty($output['active']) ? 'checked="checked"' : '';
$output['remote_cron']=!empty($output['remote_cron']) ? 'checked="checked"' : '';
$output['is_featured']=!empty($output['is_featured']) ? 'checked="checked"' : '';

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$tpl->set_file('content','user_sites_addedit.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
print $tpl->process('content','content');

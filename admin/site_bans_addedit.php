<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_bans_addedit.php
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
require_once '../includes/tables/site_bans.inc.php';
require_once '../includes/logs.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$site_bans_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['ban_id'])) {
	$ban_id=(int)$_GET['ban_id'];
	$query="SELECT a.`ban_id`,a.`ban_type`,a.`what`,a.`fk_lk_id_reason`,b.`lang_value` as `reason` FROM `{$dbtable_prefix}site_bans` a,`{$dbtable_prefix}lang_strings` b WHERE a.`ban_id`='$ban_id' AND a.`fk_lk_id_reason`=b.`fk_lk_id` AND b.`skin`='".get_default_skin_code()."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['reason']=sanitize_and_format($output['reason'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		if ($output['ban_type']==_PUNISH_BANIP_) {
			$output['what']=long2ip($output['what']);
		}
	}
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}

$output['ban_type']=vector2radios($accepted_punishments,'ban_type',$output['ban_type'],array(_PUNISH_ERROR_));
$output['default_skin']=get_default_skin_name();

$tpl->set_file('content','site_bans_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Banned Members Management';
$tplvars['css']='site_bans_addedit.css';
$tplvars['page']='site_bans_addedit';
include 'frame.php';
?>
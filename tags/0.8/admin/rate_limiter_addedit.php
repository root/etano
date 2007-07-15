<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/rate_limiter_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/logs.inc.php';
require_once '../includes/tables/rate_limiter.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output=$rate_limiter_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
} elseif (!empty($_GET['rate_id'])) {
	$rate_id=(int)$_GET['rate_id'];
	$query="SELECT * FROM `{$dbtable_prefix}rate_limiter` WHERE `rate_id`='$rate_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
	$output['error_message']='';
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='".get_default_skin_code()."' AND `fk_lk_id`='".$output['fk_lk_id_error_message']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['error_message']=mysql_result($res,0,0);
	}
	$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
}
$output['m_value']=dbtable2options("`{$dbtable_prefix}memberships`",'`m_value`','`m_name`','`m_value`',$output['m_value']);
$output['level_code']=dbtable2options("`{$dbtable_prefix}access_levels`",'`level_code`','`level_code`','`level_id`',$output['level_code']);
$output['punishment']=vector2options($accepted_punishments,$output['punishment']);
$output['default_skin']=get_default_skin_name();

$tpl->set_file('content','rate_limiter_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Limits Management';
$tplvars['page']='rate_limiter_addedit';
$tplvars['css']='rate_limiter_addedit.css';
include 'frame.php';

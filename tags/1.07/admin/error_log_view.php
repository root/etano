<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/error_log_view.php
$Revision: 322 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

if (!empty($_GET['log_id'])) {
	$log_id=(int)$_GET['log_id'];
	$query="SELECT `error` FROM `{$dbtable_prefix}error_log` WHERE `log_id`=$log_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
}

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}
$tpl->set_file('content','error_log_view.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
print $tpl->process('content','content',TPL_FINISH);

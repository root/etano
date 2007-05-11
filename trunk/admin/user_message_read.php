<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/user_message_read.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

if (isset($_GET['mail_id']) && !empty($_GET['mail_id'])) {
	$output['mail_id']=(int)$_GET['mail_id'];
	$output['o']=isset($_GET['o']) ? (int)$_GET['o'] : 0;
	$output['r']=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

	$config=get_site_option(array('datetime_format'),'core');
	$query="SELECT *,UNIX_TIMESTAMP(`date_sent`) as `date_sent` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`='".$output['mail_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['date_sent']=strftime($config['datetime_format'],$output['date_sent']);
		if ($output['message_type']==MESS_SYSTEM || empty($output['fk_user_id_other'])) {
			unset($output['fk_user_id_other']);
		}
		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	}
	$output['message_body']=bbcode2html($output['message_body']);

	$tpl->set_file('content','user_message_read.html');
	$tpl->set_var('output',$output);
	$tpl->process('content','content',TPL_OPTIONAL);
}

$tplvars['title']='Read User Message';
$tplvars['page']='user_message_read';
include 'frame.php';
?>
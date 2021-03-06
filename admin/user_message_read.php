<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_message_read.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

if (!empty($_GET['mail_id'])) {
	$output['mail_id']=(int)$_GET['mail_id'];
	$output['o']=isset($_GET['o']) ? (int)$_GET['o'] : 0;
	$output['r']=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	$config=get_site_option(array('datetime_format','time_offset'),'def_user_prefs');
	$query="SELECT *,UNIX_TIMESTAMP(`date_sent`) as `date_sent` FROM `{$dbtable_prefix}user_inbox` WHERE `mail_id`=".$output['mail_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['date_sent']=strftime($config['datetime_format'],$output['date_sent']+$config['time_offset']);
		// no need to sanitize
//		$output['subject']=sanitize_and_format($output['subject'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

		switch ($output['message_type']) {

			case MESS_MESS:
				// no need to sanitize
//				$output['message_body']=sanitize_and_format($output['message_body'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				break;

			case MESS_FLIRT:
				break;

			case MESS_SYSTEM:
				if (empty($output['_user_other'])) {
					$output['_user_other']='SYSTEM';     // translate
				}
				break;

		}
		$output['message_body']=text2smilies(bbcode2html($output['message_body']));

		if ($output['message_type']==MESS_SYSTEM || empty($output['fk_user_id_other'])) {
			unset($output['fk_user_id_other']);
		}
	}
	$output['message_body']=bbcode2html($output['message_body']);

	$tpl->set_file('content','user_message_read.html');
	$tpl->set_var('output',$output);
	$tpl->process('content','content',TPL_OPTIONAL);
}

$tplvars['title']='Read User Message';
$tplvars['page']='user_message_read';
include 'frame.php';

<?php
/******************************************************************************
Etano
===============================================================================
File:                       popup_use_tpl.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
check_login_member('saved_messages');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

// just for the non-js solution
$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$output['return']=rawurlencode($output['return2']);
$output['mail_id']=sanitize_and_format_gpc($_GET,'mail_id',TYPE_INT,0,0);

$config['bbcode_message']=get_site_option('bbcode_message','core');
$templates=array();
$jsarrays=array();
$query="SELECT `mtpl_id`,`subject`,`message_body` FROM `{$dbtable_prefix}user_mtpls` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
//	$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$jsrsrow=$rsrow;
	$jsrsrow['subject']=rawurlencode(sanitize_and_format($rsrow['subject'],TYPE_STRING,FORMAT_TEXT2HTML));
	$jsrsrow['message_body']=rawurlencode(sanitize_and_format($rsrow['message_body'],TYPE_STRING,FORMAT_TEXT2HTML));
	$jsarrays[]=$jsrsrow;
	if ($config['bbcode_message']) {
		$rsrow['message_body']=bbcode2html($rsrow['message_body']);
	}
	$templates[]=$rsrow;
}

$tpl->set_file('content','popup_use_tpl.html');
$tpl->set_var('output',$output);
$tpl->set_loop('jsarrays',$jsarrays);
$tpl->set_loop('templates',$templates);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','content',TPL_FINISH | TPL_LOOP | TPL_NOLOOP);

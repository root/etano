<?php
/******************************************************************************
Etano
===============================================================================
File:                       folders.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('manage_folders');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$my_folders=array(FOLDER_INBOX=>$GLOBALS['_lang'][110],FOLDER_OUTBOX=>$GLOBALS['_lang'][111],FOLDER_TRASH=>$GLOBALS['_lang'][112],FOLDER_SPAMBOX=>$GLOBALS['_lang'][113]);
$query="SELECT a.`folder_id`,a.`folder`,count(DISTINCT b.`mail_id`) as `total`,count(DISTINCT c.`mail_id`) as `not_read` FROM `{$dbtable_prefix}user_folders` a LEFT JOIN `{$dbtable_prefix}user_inbox` b ON a.`fk_user_id`=b.`fk_user_id` AND a.`folder_id`=b.`fk_folder_id` LEFT JOIN `{$dbtable_prefix}user_inbox` c ON a.`fk_user_id`=c.`fk_user_id` AND a.`folder_id`=c.`fk_folder_id` AND c.`is_read`=0 WHERE a.`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' GROUP BY a.`folder_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['encoded_folder']=sanitize_and_format($rsrow['folder'],TYPE_STRING,FORMAT_RUENCODE);
	$rsrow['folder']=sanitize_and_format($rsrow['folder'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$loop[]=$rsrow;
	$my_folders[$rsrow['folder_id']]=$rsrow['folder'];
}

$output['lang_267']=sanitize_and_format($GLOBALS['_lang'][267],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_268']=sanitize_and_format($GLOBALS['_lang'][268],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_269']=sanitize_and_format($GLOBALS['_lang'][269],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','folders.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][122];
$tplvars['page_title']=$GLOBALS['_lang'][122];
$tplvars['page']='folders';
$tplvars['css']='folders.css';
if (is_file('folders_left.php')) {
	include 'folders_left.php';
}
include 'frame.php';

<?php
/******************************************************************************
newdsb
===============================================================================
File:                       folders.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
require_once 'includes/tables/user_inbox.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(14);

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$query="SELECT a.`folder_id`,a.`folder`,count(DISTINCT b.`mail_id`) as `total`,count(DISTINCT c.`mail_id`) as `not_read` FROM `{$dbtable_prefix}user_folders` a LEFT JOIN `{$dbtable_prefix}user_inbox` b ON a.`fk_user_id`=b.`fk_user_id` AND a.`folder_id`=b.`fk_folder_id` LEFT JOIN `{$dbtable_prefix}user_inbox` c ON a.`fk_user_id`=c.`fk_user_id` AND a.`folder_id`=c.`fk_folder_id` AND c.`is_read`=0 WHERE a.`fk_user_id`='".$_SESSION['user']['user_id']."' GROUP BY a.`folder_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['folder']=sanitize_and_format($rsrow['folder'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$loop[]=$rsrow;
}

$tpl->set_file('content','folders.html');
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Manage folders';     // translate
$tplvars['page_title']='Your personal folders';
$tplvars['page']='folders';
$tplvars['css']='folders.css';
include 'frame.php';
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       flirt_send_left.php
$Revision: 51 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_friends=array();

$query="SELECT * FROM `{$dbtable_prefix}message_filters` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `filter_type`='"._FILTER_USER_."' AND `field_value`='".$uid."' AND `fk_folder_id`='".FOLDER_SPAMBOX."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$tpl->set_var('unblock_user',true);
}

$tpl->set_file('left_content','flirt_send_left.html');
$tpl->set_loop('user_friends',$user_friends);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_NOLOOP | TPL_OPTIONAL);
?>
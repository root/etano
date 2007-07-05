<?php
/******************************************************************************
Etano
===============================================================================
File:                       message_send_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$tpl->set_file('left_content','message_send_left.html');

$my_folders=array(FOLDER_INBOX=>'INBOX',FOLDER_OUTBOX=>'SENT',FOLDER_TRASH=>'Trash',FOLDER_SPAMBOX=>'SPAMBOX'); // translate this
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$my_folders[$rsrow[0]]=sanitize_and_format($rsrow[1],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
}

$query="SELECT `fk_folder_id`,count(*) FROM `{$dbtable_prefix}user_inbox` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' AND `is_read`=0 AND `del`=0 GROUP BY `fk_folder_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$num_messages=array();
while ($rsrow=mysql_fetch_row($res)) {
	$num_messages[$rsrow[0]]=$rsrow[1];
}

$loopfolders=array();
$i=0;
foreach ($my_folders as $k=>$v) {
	$loopfolders[$i]['fid']=$k;
	$loopfolders[$i]['folder']=$v;
	if (!empty($num_messages[$k])) {
		$loopfolders[$i]['folder'].=' ('.$num_messages[$k].')';
	}
	++$i;
}
$tpl->set_loop('loopfolders',$loopfolders);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_loop('loopfolders');
unset($loopfolders);
?>
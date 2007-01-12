<?php
/******************************************************************************
newdsb
===============================================================================
File:                       mailbox_left.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$tpl->set_file('left_content','mailbox_left.html');
$loopfolders=array();
$i=0;
foreach ($folders as $k=>$v) {
	$loopfolders[$i]['folder_id']=$k;
	$loopfolders[$i]['folder']=$v;
	++$i;
}
$tpl->set_loop('loopfolders',$loopfolders);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_loop('loopfolders');
?>
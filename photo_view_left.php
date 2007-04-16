<?php
/******************************************************************************
newdsb
===============================================================================
File:                       photo_view_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_views=array();

$tpl->set_file('left_content','photo_view_left.html');
if (isset($output['fk_user_id']) && isset($_SESSION['user']['user_id']) && $output['fk_user_id']==$_SESSION['user']['user_id']) {
	$tpl->set_var('own_photo',true);
}
$tpl->process('left_content','left_content',TPL_OPTIONAL);
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_profile_left.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_views=array();

$tpl->set_file('left_content','my_profile_left.html');
$tpl->set_loop('user_views',$user_views);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_NOLOOP);
?>
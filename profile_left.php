<?php
/******************************************************************************
newdsb
===============================================================================
File:                       profile_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_friends=array();

$tpl->set_file('left_content','profile_left.html');
$tpl->set_loop('user_friends',$user_friends);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_NOLOOP | TPL_OPTIONAL);
?>
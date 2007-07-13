<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_profile_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$user_views=array();

$tpl->set_file('left_content','my_profile_left.html');
$tpl->set_loop('user_views',$user_views);
$tpl->process('left_content','left_content',TPL_LOOP | TPL_NOLOOP);

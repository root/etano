<?php
/******************************************************************************
newdsb
===============================================================================
File:                       user_photos_left.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$tpl->set_file('left_content','photo_search_left.html');
if (isset($input['uid']) && empty($input['uid'])) {
	unset($input['uid']);
}
$tpl->set_var('output',$input);
$tpl->process('left_content','left_content',TPL_OPTIONAL);
?>
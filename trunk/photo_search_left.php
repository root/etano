<?php
/******************************************************************************
Etano
===============================================================================
File:                       user_photos_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

$tpl->set_file('left_content','photo_search_left.html');
if (isset($input['uid'])) {
	if (empty($input['uid'])) {
		unset($input['uid']);
	} elseif (isset($_SESSION['user']['user_id'])) {
		require_once 'includes/network_functions.inc.php';
		if (is_network_member($_SESSION['user']['user_id'],$input['uid'],NET_BLOCK)) {
			$input['unblock_user']=true;
		}
	}
}

$tpl->set_var('output',$input);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_OPTIONAL);
?>
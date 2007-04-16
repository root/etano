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
if (isset($output['uid'])) {
	$uid=$output['uid'];
	unset($output);
	$output['uid']=$uid;
	// get some friends
	$output['user_friends']=get_network_members($output['uid'],NET_FRIENDS,4);
	if (!empty($output['user_friends'])) {
		require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
		$user_cache=new user_cache(get_my_skin());
		$output['user_friends']=$user_cache->get_cache_array($output['user_friends'],'result_user');
		$output['user_friends']=smart_table($output['user_friends'],1,'gallery_view');
		unset($user_cache);
	} else {
		unset($output['user_friends']);
	}

	if (isset($_SESSION['user']['user_id']) && is_network_member($_SESSION['user']['user_id'],$output['uid'],NET_BLOCK)) {
		$output['unblock_user']=true;
	}
}

$tpl->set_file('left_content','profile_left.html');
$tpl->set_var('output',$output);
$tpl->process('left_content','left_content',TPL_OPTIONAL);
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       profile_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

if (isset($output['uid'])) {
	$uid=$output['uid'];
	unset($output);
	$output['uid']=$uid;

	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && is_network_member($_SESSION[_LICENSE_KEY_]['user']['user_id'],$output['uid'],NET_BLOCK)) {
		$output['unblock_user']=true;
	}
}

$tpl->set_file('left_content','profile_left.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_OPTIONAL);

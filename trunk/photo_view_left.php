<?php
/******************************************************************************
Etano
===============================================================================
File:                       photo_view_left.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

$user_views=array();

$tpl->set_file('left_content','photo_view_left.html');
if (isset($output['fk_user_id']) && !empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
	$tpl->set_var('own_photo',true);
}
if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	if (is_network_member($_SESSION[_LICENSE_KEY_]['user']['user_id'],$output['fk_user_id'],NET_BLOCK)) {
		$output['unblock_user']=true;
	}
}
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('left_content','left_content',TPL_OPTIONAL);

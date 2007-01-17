<?php
/******************************************************************************
newdsb
===============================================================================
File:                       profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(2);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$uid=0;
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(string)((int)$_GET['uid']);
} elseif (isset($_GET['user'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	$uid=get_userid_by_user($user);
} elseif (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id'])) {
	$uid=(string)$_SESSION['user']['user_id'];
} else {
	redirect2page('index.php');
}

if (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']==$uid) {
	redirect2page('my_profile.php');
}

$categs=array();
$profile=array();
require_once 'includes/classes/user_cache.class.php';
$user_cache=new user_cache(get_my_skin());
$temp=$user_cache->get_cache($uid,'profile');
if (!empty($temp)) {
	$tpl->set_var('content',$temp);
	$j=0;
	foreach ($_pcats as $pcat_id=>$pcat) {
		if (((int)$pcat['access_level']) & ((int)$_SESSION['user']['membership'])) {
			$temp=$user_cache->get_cache($uid,'categ_'.$pcat_id);
			if (!empty($temp)) {
				$categs[$j]['content']=$temp;
				// if you prefer a custom layout use {profile.categ_1},{profile.categ_2},etc in <skin>/static/profile.html,
				// remove the loop and remove TPL_LOOP from $tpl->process() below
				$profile['categ_'.$pcat_id]=$temp;
				++$j;
			}
		} else {
			// not allowed to view this member info
// maybe we should say something here like "upgrade your membership to view this info"...
		}
	}
} else {
	$tpl->set_var('content','');
}

if (isset($_SESSION['user']['user_id']) && $uid==$_SESSION['user']['user_id']) {
	$tpl->set_var('editable',true);
}
$tplvars['pic_width']=get_site_option('pic_width','core_photo');
$tpl->set_loop('categs',$categs);
$tpl->set_var('profile',$profile);
$tpl->set_var('uid',$uid);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');

if (is_file('profile_left.php')) {
	include 'profile_left.php';
}
$tplvars['title']='Member Profile';
include 'frame.php';
?>
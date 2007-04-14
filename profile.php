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
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(2);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$uid=0;
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(string)((int)$_GET['uid']);
} elseif (isset($_GET['user'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__html2format[HTML_TEXTFIELD]);
	$uid=get_userid_by_user($user);
} elseif (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id'])) {
	$uid=(string)$_SESSION['user']['user_id'];
} else {
	redirect2page('index.php');
}

if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']==$uid) {
	redirect2page('my_profile.php');
}

$output=array();
$query="SELECT `fk_user_id` as `uid`,`_user` as `user`,`_photo` as `photo` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`='$uid' AND `status`='".STAT_APPROVED."' AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
}

$user_photos=array();
$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`='".$output['uid']."' AND `is_private`=0 AND `status`='".STAT_APPROVED."' AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	while (count($user_photos)<3 && $rsrow=mysql_fetch_assoc($res)) {
		if (is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$user_photos[]=$rsrow;
		}
	}
	$user_photos[0]['class']='first';
	$output['num_photos']=mysql_num_rows($res);
}

$categs=array();
require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
$user_cache=new user_cache(get_my_skin());

$j=0;
foreach ($_pcats as $pcat_id=>$pcat) {
	if (((int)$pcat['access_level']) & ((int)$_SESSION['user']['membership'])) {
		$temp=$user_cache->get_cache($output['uid'],'categ_'.$pcat_id);
		if (!empty($temp)) {
			$categs[$j]['content']=$temp;
			// if you prefer a custom layout use {profile.categ_1},{profile.categ_2},etc in <skin>/profile.html,
			// remove $tpl->set_loop and remove TPL_LOOP from $tpl->process() below
			$output['categ_'.$pcat_id]=$temp;
			++$j;
		}
	} else {
		// not allowed to view this member info
// maybe we should say something here like "upgrade your membership to view this info"...
	}
}

$categs[count($categs)-1]['class']='last';

$output['return']='profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','profile.html');
$tplvars['pic_width']=get_site_option('pic_width','core_photo');
$tpl->set_loop('categs',$categs);
$tpl->set_loop('user_photos',$user_photos);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
unset($categs);

$tplvars['title']=sprintf('%s Profile',$output['user']);
$tplvars['page_title']=$output['user'];
$tplvars['page']='profile';
$tplvars['css']='profile.css';
if (is_file('profile_left.php')) {
	include 'profile_left.php';
}
include 'frame.php';
?>
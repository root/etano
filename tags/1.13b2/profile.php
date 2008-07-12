<?php
/******************************************************************************
Etano
===============================================================================
File:                       profile.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/network_functions.inc.php';
check_login_member('profile_view');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$uid=0;
if (!empty($_GET['uid'])) {
	$uid=(string)((int)$_GET['uid']);
} elseif (isset($_GET['user'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	$uid=get_userid_by_user($user);
} elseif (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
	$uid=(string)$_SESSION[_LICENSE_KEY_]['user']['user_id'];
} else {
	redirect2page('index.php');
}

if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $_SESSION[_LICENSE_KEY_]['user']['user_id']==$uid) {
	redirect2page('my_profile.php');
}

$output=array();
// we don't care about user status because the cache generator will generate the profile for the user only if status is approved
// also _photo is set only with approved photos.
$query="SELECT `fk_user_id` as `uid`,`_user` as `user`,`_photo` as `photo` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=$uid AND `del`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
}

$user_photos=array();
$loop_friends=array();
$categs=array();
$loop_comments=array();
if (!empty($output)) {
	if (!empty($_list_of_online_members[$output['uid']])) {
		$output['is_online']=true;
	}
	// user photos
	$query="SELECT `photo_id`,`photo` FROM `{$dbtable_prefix}user_photos` WHERE `fk_user_id`=".$output['uid']." AND `is_private`=0 AND `status`=".STAT_APPROVED." AND `del`=0";
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

	// get the profile
	require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
	$user_cache=new user_cache();

	$j=0;
	foreach ($_pcats as $pcat_id=>$pcat) {
		if (((int)$pcat['access_level']) & ((int)$_SESSION[_LICENSE_KEY_]['user']['membership'])) {
			$temp=$user_cache->get_categ($output['uid'],$pcat_id);
			if (!empty($temp)) {
				$categs[$j]['content']=$temp;
				// if you prefer a custom layout use {profile.categ_1},{profile.categ_2},etc in <skin>/profile.html,
				// uncomment the line below, remove $tpl->set_loop,
//				$output['categ_'.$pcat_id]=$temp;
				++$j;
			}
		} else {
			// not allowed to view this member info
	// maybe we should say something here like "upgrade your membership to view this info"...
		}
	}
	$categs[count($categs)-1]['class']='last';

	// get some friends
	$loop_friends=get_network_members($output['uid'],NET_FRIENDS,4);
	if (!empty($loop_friends)) {
		$loop_friends=$user_cache->get_cache_tpl($loop_friends,'result_user');
	}
	unset($user_cache);

	// comments
	$loop_comments=create_comments_loop('user',$output['uid'],$output);

	$output['pic_width']=get_site_option('pic_width','core_photo');
	$tplvars['title']=sprintf($GLOBALS['_lang'][152],$output['user']);
	$tplvars['page_title']=$output['user'];
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][7];
	redirect2page('info.php',$topass);
}
$output['lang_273']=sanitize_and_format($GLOBALS['_lang'][273],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_274']=sanitize_and_format($GLOBALS['_lang'][274],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_256']=sanitize_and_format($GLOBALS['_lang'][256],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','profile.html');
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('categs',$categs);
$tpl->set_loop('user_photos',$user_photos);
$tpl->set_loop('loop_comments',$loop_comments);
$tpl->set_loop('loop_friends',$loop_friends);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('categs');
$tpl->drop_loop('user_photos');
unset($categs);
unset($user_photos);

$tplvars['page']='profile';
$tplvars['css']='profile.css';
if (is_file('profile_left.php')) {
	include 'profile_left.php';
}
include 'frame.php';
update_stats($uid,'pviews',1);
add_member_score($uid,'pview');

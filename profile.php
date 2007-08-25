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
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/network_functions.inc.php';
check_login_member('profile_view');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$uid=0;
if (!empty($_GET['uid'])) {
	$uid=(string)((int)$_GET['uid']);
} elseif (isset($_GET['user'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	$uid=get_userid_by_user($user);
} elseif (!empty($_SESSION['user']['user_id'])) {
	$uid=(string)$_SESSION['user']['user_id'];
} else {
	redirect2page('index.php');
}

if (!empty($_SESSION['user']['user_id']) && $_SESSION['user']['user_id']==$uid) {
	redirect2page('my_profile.php');
}

$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);

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
	$user_cache=new user_cache(get_my_skin());

	$j=0;
	foreach ($_pcats as $pcat_id=>$pcat) {
		if (((int)$pcat['access_level']) & ((int)$_SESSION['user']['membership'])) {
			$temp=$user_cache->get_cache($output['uid'],'categ_'.$pcat_id);
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
		$loop_friends=$user_cache->get_cache_beta($loop_friends,'result_user','tpl',$tpl);
	}
	unset($user_cache);

	// comments
	$config=get_site_option(array('use_captcha','bbcode_comments','smilies_comm'),'core');
	$query="SELECT a.`comment_id`,a.`comment`,a.`fk_user_id`,a.`_user` as `user`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`_photo` as `photo` FROM `{$dbtable_prefix}profile_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_parent_id`=".$output['uid']." AND a.`status`=".STAT_APPROVED." ORDER BY a.`comment_id` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		// if someone has asked to edit his/her comment
		if ($edit_comment==$rsrow['comment_id']) {
			$output['comment_id']=$rsrow['comment_id'];
			$output['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
		}
		$rsrow['date_posted']=strftime($_SESSION['user']['prefs']['datetime_format'],$rsrow['date_posted']+$_SESSION['user']['prefs']['time_offset']);
		$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (!empty($config['bbcode_comments'])) {
			$rsrow['comment']=bbcode2html($rsrow['comment']);
		}
		if (!empty($config['smilies_comm'])) {
			$rsrow['comment']=text2smilies($rsrow['comment']);
		}
		// allow showing the edit links to rightfull owners
		if (!empty($_SESSION['user']['user_id']) && $rsrow['fk_user_id']==$_SESSION['user']['user_id']) {
			$rsrow['editme']=true;
		}

		if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
			unset($rsrow['fk_user_id']);
		} else {
			if (isset($_list_of_online_members[$rsrow['fk_user_id']])) {
				$rsrow['is_online']='is_online';
				$rsrow['user_online_status']='is online';	// translate
			} else {
				$rsrow['user_online_status']='is offline';	// translate
			}
		}
		if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
			$rsrow['photo']='no_photo.gif';
		}
		$loop_comments[]=$rsrow;
	}

	if (!empty($loop_comments)) {
		$output['num_comments']=count($loop_comments);
		$output['show_comments']=true;
	}

	if (get_user_settings($output['uid'],'def_user_prefs','profile_comments')) {
		// may I post comments please?
		if (allow_at_level('write_comments',$_SESSION['user']['membership'])) {
			$output['allow_comments']=true;
			if (empty($_SESSION['user']['user_id'])) {
				if ($config['use_captcha']) {
					require_once 'includes/classes/sco_captcha.class.php';
					$c=new sco_captcha(_BASEPATH_.'/includes/fonts',4);
					$_SESSION['captcha_word']=$c->gen_rnd_string(4);
					$output['rand']=make_seed();
					$output['use_captcha']=true;
				}
			}
			// would you let me use bbcode?
			if (!empty($config['bbcode_comments'])) {
				$output['bbcode_comments']=true;
			}
			// if we came back after an error get what was previously posted
			if (!empty($_SESSION['topass']['input'])) {
				$output=array_merge($output,$_SESSION['topass']['input']);
				unset($_SESSION['topass']['input']);
			}
		} else {
			unset($output['allow_comments']);
		}
	} else {
		unset($output['allow_comments']);
	}

	$output['pic_width']=get_site_option('pic_width','core_photo');
	$tplvars['title']=sprintf('%s Profile',$output['user']);
	$tplvars['page_title']=$output['user'];
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Member not found';
	redirect2page('info.php',$topass);
}

$output['return2me']='profile.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	if (!empty($edit_comment)) {
		$_SERVER['QUERY_STRING']=str_replace('&edit_comment='.$edit_comment,'',$_SERVER['QUERY_STRING']);
	}
	$output['return2me'].='?'.str_replace('&','&amp;',$_SERVER['QUERY_STRING']);
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
include 'home_left.php';
include 'frame2.php';
update_stats($uid,'pviews',1);
add_member_score($uid,'pview');

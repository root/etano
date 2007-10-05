<?php
/******************************************************************************
Etano
===============================================================================
File:                       blog_post_view.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('read_blogs');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$post_id=sanitize_and_format_gpc($_GET,'pid',TYPE_INT,0,0);
$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);

$output=array();
$loop=array();
if (!empty($post_id)) {
	// no need to check the status of the post ( AND `status`=".STAT_APPROVED)
	$query="SELECT `fk_user_id`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=$post_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		require_once _BASEPATH_.'/includes/classes/blog_posts_cache.class.php';
		$blog_posts_cache=new blog_posts_cache();
		$output=array_merge($output,$blog_posts_cache->get_post($post_id,false));
		unset($blog_posts_cache);
		$diff2gm=(int)mktime(date('H'))-(int)gmmktime(date('H'));
		if ($output['date_posted']-$diff2gm>$page_last_modified_time) {
			$page_last_modified_time=$output['date_posted']-$diff2gm;
		}
		$output['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$output['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
		if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
			$output['post_owner']=true;
		}
		if (isset($_list_of_online_members[$output['fk_user_id']])) {
			$output['is_online']='member_online';
			$output['user_online_status']='is online';	// translate
		} else {
			$output['user_online_status']='is offline';	// translate
		}

		$config=get_site_option(array('use_captcha','bbcode_comments','smilies_comm'),'core');
		// comments
		$query="SELECT a.`comment_id`,a.`comment`,a.`fk_user_id`,a.`_user` as `user`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_parent_id`=".$output['post_id']." AND a.`status`=".STAT_APPROVED." ORDER BY a.`comment_id` ASC";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			if ($rsrow['date_posted']-$diff2gm>$page_last_modified_time) {
				$page_last_modified_time=$rsrow['date_posted']-$diff2gm;
			}
			// if someone has asked to edit his/her comment & js is disabled
			if ($edit_comment==$rsrow['comment_id']) {
				$output['comment_id']=$rsrow['comment_id'];
				$output['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
			}
			$rsrow['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$rsrow['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
			$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			if (!empty($config['bbcode_comments'])) {
				$rsrow['comment']=bbcode2html($rsrow['comment']);
			}
			if (!empty($config['smilies_comm'])) {
				$rsrow['comment']=text2smilies($rsrow['comment']);
			}
			// allow showing the edit links to rightfull owners
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $rsrow['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
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
			$loop[]=$rsrow;
		}

		if (!empty($loop)) {
			$output['num_comments']=count($loop);
			$output['show_comments']=true;
		}

		if (!empty($output['allow_comments'])) {
			// may I post comments please?
			if (allow_at_level('write_comments',$_SESSION[_LICENSE_KEY_]['user']['membership'])) {
				if (empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
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
				if (isset($_SESSION['topass']['input'])) {
					$output=array_merge($output,$_SESSION['topass']['input']);
					unset($_SESSION['topass']['input']);
				}
			} else {
				unset($output['allow_comments']);
			}
		} else {
			unset($output['allow_comments']);
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid blog selected';
		redirect2page('info.php',$topass);
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Invalid blog selected';
	redirect2page('info.php',$topass);
}

$output['return2me']='blog_post_view.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	if (!empty($edit_comment)) {
		$_SERVER['QUERY_STRING']=str_replace('&edit_comment='.$edit_comment,'',$_SERVER['QUERY_STRING']);
	}
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','blog_post_view.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=sprintf('Blogs - %1$s - %2$s',$output['blog_name'],$output['title']);
$tplvars['page_title']=sprintf('%1$s : %2$s','<a href="'.$tplvars['relative_url'].'blog_view.php?bid='.$output['fk_blog_id'].'">'.$output['blog_name'].'</a>',$output['title']);	// translate this
$tplvars['page']='blog_post_view';
$tplvars['css']='blog_post_view.css';
if (is_file('blog_post_view_left.php')) {
	include 'blog_post_view_left.php';
}
include 'frame.php';
if (!empty($post_id) && isset($output['fk_user_id']) && ((!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']!=$_SESSION[_LICENSE_KEY_]['user']['user_id']) || empty($_SESSION[_LICENSE_KEY_]['user']['user_id']))) {
	$query="UPDATE `{$dbtable_prefix}blog_posts` SET `stat_views`=`stat_views`+1 WHERE `post_id`=$post_id";
	@mysql_query($query);
}

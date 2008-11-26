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

//define('CACHE_LIMITER','private');
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
check_login_member('read_blogs');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');
$post_id=sanitize_and_format_gpc($_GET,'pid',TYPE_INT,0,0);

$output=array();
$loop_comments=array();
if (!empty($post_id)) {
	// no need to check the status of the post ( AND `status`=".STAT_APPROVED)
	$query="SELECT `fk_user_id`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `post_id`=$post_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		require _BASEPATH_.'/includes/classes/blog_posts_cache.class.php';
		$blog_posts_cache=new blog_posts_cache();
		if ($temp=$blog_posts_cache->get_post($post_id,false)) {
			$output=array_merge($output,$temp);
			if ($output['date_posted']>$page_last_modified_time) {
				$page_last_modified_time=$output['date_posted'];
			}
			$output['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$output['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $output['fk_user_id']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
				$output['post_owner']=true;
			}
			if (isset($_list_of_online_members[$output['fk_user_id']])) {
				$output['is_online']='member_online';
				$output['user_online_status']=$GLOBALS['_lang'][102];
			} else {
				$output['user_online_status']=$GLOBALS['_lang'][103];
			}
			// comments
			$loop_comments=create_comments_loop('blog',$output['post_id'],$output);
		} else {
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$GLOBALS['_lang'][2];
			redirect2page('info.php',$topass);
		}
		unset($blog_posts_cache);
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][2];
		redirect2page('info.php',$topass);
	}
} else {
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']=$GLOBALS['_lang'][2];
	redirect2page('info.php',$topass);
}
$output['lang_256']=sanitize_and_format($GLOBALS['_lang'][256],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='blog_post_view.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','blog_post_view.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop_comments',$loop_comments);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop_comments');
unset($loop_comments);

$tplvars['title']=sprintf($GLOBALS['_lang'][222],$output['blog_name'],$output['title']);
$tplvars['page_title']=sprintf($GLOBALS['_lang'][221],'<a href="'.$tplvars['relative_url'].'blog_view.php?bid='.$output['fk_blog_id'].'">'.$output['blog_name'].'</a>',$output['title']);
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

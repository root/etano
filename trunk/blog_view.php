<?php
/******************************************************************************
Etano
===============================================================================
File:                       blog_view.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('read_blogs');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$blog=array();
$output=array();
$loop=array();
if (isset($_GET['bid']) && !empty($_GET['bid'])) {
	$blog_id=(string)(int)$_GET['bid'];
	if (is_file(_CACHEPATH_.'/blogs/'.$blog_id{0}.'/'.$blog_id.'/blog.inc.php')) {
		include _CACHEPATH_.'/blogs/'.$blog_id{0}.'/'.$blog_id.'/blog.inc.php';
	}
	$output=&$blog;

	$year=sanitize_and_format_gpc($_GET,'y',TYPE_INT,0,0);
	$month=sanitize_and_format_gpc($_GET,'m',TYPE_INT,0,0);
	if (empty($year)) {
		$query="SELECT YEAR(`date_posted`),MONTH(`date_posted`) FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id`='$blog_id' ORDER BY `date_posted` DESC LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($year,$month)=mysql_fetch_row($res);
		}
	} elseif (empty($month)) {
		$query="SELECT YEAR(`date_posted`),MONTH(`date_posted`) FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id`='$blog_id' AND YEAR(`date_posted`)='$year' ORDER BY `date_posted` DESC LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			list($year,$month)=mysql_fetch_row($res);
		}
	}
	if (!empty($year) && !empty($month)) {
		$config=get_site_option(array('bbcode_blogs'),'core_blog');
		$month=str_pad($month,2,'0',STR_PAD_LEFT);
		// no need to check the status of the posts ( AND `status`='".STAT_APPROVED."')
		$query="SELECT `post_id`,`stat_comments`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id`='$blog_id' AND `date_posted`>'{$year}{$month}00000000' AND `date_posted`<='{$year}{$month}31235959' ORDER BY `post_id` DESC";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$post_ids=array();
			$temp=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$post_ids[]=$rsrow['post_id'];
				$temp[$rsrow['post_id']]=$rsrow;
			}
			require_once _BASEPATH_.'/includes/classes/blog_posts_cache.class.php';
			$blog_posts_cache=new blog_posts_cache();
			$loop=$blog_posts_cache->get_tpl_array($post_ids,false);
			unset($blog_posts_cache);
			for ($i=0;isset($loop[$i]);++$i) {
				$loop[$i]['date_posted']=strftime($_SESSION['user']['prefs']['datetime_format'],$loop[$i]['date_posted']+$_SESSION['user']['prefs']['time_offset']);
				if (isset($_SESSION['user']['user_id']) && $loop[$i]['fk_user_id']==$_SESSION['user']['user_id']) {
					$loop[$i]['editable']=true;
				}
				$loop[$i]['stat_comments']=$temp[$loop[$i]['post_id']]['stat_comments'];
				$loop[$i]['allow_comments']=$temp[$loop[$i]['post_id']]['allow_comments'];
			}
		}
	}
}

$output['return2me']='blog_view.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','blog_view.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Blog posts';
$tplvars['page_title']=isset($blog['blog_name']) ? $blog['blog_name'] : '';
$tplvars['page']='blog_view';
$tplvars['css']='blog_view.css';
if (is_file('blog_view_left.php')) {
	include 'blog_view_left.php';
}
include 'frame.php';
?>
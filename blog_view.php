<?php
/******************************************************************************
newdsb
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
require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(10);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$blog=array();
$loop=array();
if (isset($_GET['bid']) && !empty($_GET['bid'])) {
	$blog_id=(string)(int)$_GET['bid'];
	if (is_file(_CACHEPATH_.'/blogs/'.$blog_id{0}.'/'.$blog_id.'/blog.inc.php')) {
		include _CACHEPATH_.'/blogs/'.$blog_id{0}.'/'.$blog_id.'/blog.inc.php';
	}

	$is_auth_user=false;
	if (isset($_SESSION['user']['user_id'])) {
		$is_auth_user=true;
	}
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
		$query="SELECT `post_id`,`title`,`post_content`,`_user` as `user`,`fk_user_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted`,`stat_comments`,`allow_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `fk_blog_id`='$blog_id' AND `date_posted`>'{$year}{$month}00000000' AND `date_posted`<='{$year}{$month}31235959' AND `status`='".STAT_APPROVED."' ORDER BY `date_posted` DESC";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['date_posted']=strftime($_user_settings['datetime_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
			if (empty($rsrow['fk_user_id'])) {
				unset($rsrow['fk_user_id']);
			}
			if (empty($rsrow['allow_comments'])) {
				unset($rsrow['allow_comments']);
			}
			if ($is_auth_user && $rsrow['fk_user_id']==$_SESSION['user']['user_id']) {
				$rsrow['editable']=true;
			}
			$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
			$rsrow['post_content']=sanitize_and_format($rsrow['post_content'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
			if (!empty($config['bbcode_blogs'])) {
				$rsrow['post_content']=bbcode2html($rsrow['post_content']);
			};
			$loop[]=$rsrow;
		}
	}
}

$tpl->set_file('content','blog_view.html');
$tpl->set_var('blog',$blog);
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
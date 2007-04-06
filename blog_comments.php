<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_comments.php
$Revision: 72 $
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
check_login_member(13);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$post_id=sanitize_and_format_gpc($_GET,'pid',TYPE_INT,0,0);
$tplvars['bbcode_comments']=get_site_option('bbcode_comments','core');

$output=array();
$loop=array();
if (!empty($post_id)) {
	$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`title`,a.`post_content`,a.`allow_comments`,a.`fk_blog_id`,b.`blog_name` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_blogs` b WHERE a.`post_id`='$post_id' AND a.`status`='".STAT_APPROVED."' AND a.`fk_blog_id`=b.`blog_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output['title']=sanitize_and_format($output['title'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$output['post_content']=sanitize_and_format($output['post_content'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

		if (!empty($output['allow_comments'])) {
			$query="SELECT a.`comment`,a.`fk_user_id`,a.`_user` as `user`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_post_id`='".$output['post_id']."' AND a.`status`=".STAT_APPROVED." ORDER BY a.`date_posted` ASC";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
				if (!empty($tplvars['bbcode_comments'])) {
					$rsrow['comment']=bbcode2html($rsrow['comment']);
				}
				if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
					unset($rsrow['fk_user_id']);
				}
				if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
					$rsrow['photo']='no_photo.gif';
				}
				$loop[]=$rsrow;
			}
			if (allow_at_level(9,$_SESSION['user']['membership'])) {
				$tpl->set_var('allow_comments',true);
			}
		}
	}
}

if (empty($tplvars['bbcode_comments'])) {
	unset($tplvars['bbcode_comments']);
}
$tpl->set_file('content','blog_comments.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('comments');

$tplvars['title']=sprintf('%1s - %2s - Comments',$output['blog_name'],$output['title']);
$tplvars['page_title']=sprintf('Post a comment on: %1s','<a href="blog_view.php?bid='.$output['fk_blog_id'].'">'.$output['blog_name'].'</a>');	// translate this
$tplvars['page']='blog_comments';
$tplvars['css']='blog_comments.css';
if (is_file('blog_comments_left.php')) {
	include 'blog_comments_left.php';
}
include 'frame.php';
?>
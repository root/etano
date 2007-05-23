<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/blog_post_view.php
$Revision: 72 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$post_id=sanitize_and_format_gpc($_GET,'pid',TYPE_INT,0,0);
$edit_comment=sanitize_and_format_gpc($_GET,'edit_comment',TYPE_INT,0,0);

$output=array();
$loop=array();
if (!empty($post_id)) {
	$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user`,a.`fk_blog_id`,b.`blog_name`,a.`title`,a.`post_content`,a.`allow_comments` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_blogs` b WHERE a.`post_id`='$post_id' AND a.`fk_blog_id`=b.`blog_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
//		$output['date_posted']=strftime($_user_settings['datetime_format'],$output['date_posted']+$_user_settings['time_offset']);

		if (!empty($output['allow_comments'])) {
			// may I see any comment?
			$output['show_comments']=true;
			$config=get_site_option(array('use_captcha','bbcode_comments','smilies_comm'),'core');
			$query="SELECT a.`comment_id`,a.`comment`,a.`fk_user_id`,a.`_user` as `user`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`fk_user_id`=b.`fk_user_id` WHERE a.`fk_parent_id`='".$output['post_id']."' ORDER BY a.`comment_id` ASC";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				// if someone has asked to edit his/her comment
				if ($edit_comment==$rsrow['comment_id']) {
					$output['comment_id']=$rsrow['comment_id'];
					$output['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
				}
//				$rsrow['date_posted']=strftime($_user_settings['datetime_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
				$rsrow['comment']=sanitize_and_format($rsrow['comment'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				if (!empty($config['bbcode_comments'])) {
					$rsrow['comment']=bbcode2html($rsrow['comment']);
				}
				if (!empty($config['smilies_comm'])) {
					$rsrow['comment']=text2smilies($rsrow['comment']);
				}
				if (empty($rsrow['fk_user_id'])) {	// for the link to member profile
					unset($rsrow['fk_user_id']);
				}
				if (empty($rsrow['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$rsrow['photo'])) {
					$rsrow['photo']='no_photo.gif';
				}
				$loop[]=$rsrow;
			}

			if (!empty($loop)) {
				$output['num_comments']=count($loop);
			}
		}
	} else {
		trigger_error('Invalid blog selected',E_USER_ERROR);
	}
} else {
	trigger_error('Invalid blog selected',E_USER_ERROR);
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
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=sprintf('View a blog post');
$tplvars['page']='blog_post_view';
$tplvars['css']='blog_post_view.css';
include 'frame.php';
?>
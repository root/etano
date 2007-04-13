<?php
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/user_functions.inc.php';
require_once '../../includes/classes/modman.class.php';

$short_blog_chars=600;
$config=get_site_option(array('bbcode_blogs'),'core_blog');

$modman=new modman();

$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`status`='".STAT_APPROVED."'";
//$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`status`='".STAT_APPROVED."' AND a.`last_changed`>=DATE_SUB(now(),INTERVAL 12 MINUTE)";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($blog=mysql_fetch_assoc($res)) {
	$blog['title']=sanitize_and_format($blog['title'],TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	$post_content_short=substr($blog['post_content'],0,strrpos(substr($blog['post_content'],0,$short_blog_chars),' '));
	$post_content_short=sanitize_and_format($post_content_short,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	$blog['post_content']=sanitize_and_format($blog['post_content'],TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	if (!empty($config['bbcode_blogs'])) {
		$blog['post_content']=bbcode2html($blog['post_content']);
		$post_content_short=bbcode2html($post_content_short);
	}
	if (empty($blog['photo']) || !is_file(_PHOTOPATH_.'/t1/'.$blog['photo'])) {
		$blog['photo']='no_photo.gif';
	} else {
		$blog['has_photo']=true;
	}
	if (empty($blog['fk_user_id'])) {
		unset($blog['fk_user_id']);
	}

	$towrite='<?php $post='.var_export($blog,true).';';
	$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'.inc.php',$towrite);

	$blog['post_content']=$post_content_short;
	$towrite='<?php $post='.var_export($blog,true).';';
	$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'_short.inc.php',$towrite);
}
?>
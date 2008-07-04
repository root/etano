<?php
require_once dirname(__FILE__).'/../../includes/common.inc.php';
require_once dirname(__FILE__).'/../../includes/classes/phemplate.class.php';
require_once dirname(__FILE__).'/../../includes/user_functions.inc.php';
require_once dirname(__FILE__).'/../../includes/classes/fileop.class.php';

$short_blog_chars=1000;
$config=get_site_option(array('bbcode_blogs','use_smilies'),'core_blog');

$fileop=new fileop();
$blog_details=array();
$blog_archive=array();
$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,UNIX_TIMESTAMP(a.`last_changed`) as `last_changed`,a.`fk_user_id`,a.`_user` as `user`,a.`fk_blog_id`,a.`title`,a.`post_content`,b.`_photo` as `photo`,c.`blog_name` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_profiles` b,`{$dbtable_prefix}user_blogs` c WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`fk_blog_id`=c.`blog_id` AND a.`status`=".STAT_APPROVED;
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($blog=mysql_fetch_assoc($res)) {
	$last_approved=$blog['last_changed'];
	unset($blog['last_changed']);
	$blog['fk_blog_id']=(string)$blog['fk_blog_id'];
	$blog['title']=sanitize_and_format($blog['title'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	$post_content_short=substr($blog['post_content'],0,strrpos(substr($blog['post_content'],0,$short_blog_chars),' '));
	$post_content_short=sanitize_and_format($post_content_short,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$blog['post_content']=sanitize_and_format($blog['post_content'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	if (!empty($config['bbcode_blogs'])) {
		$blog['post_content']=bbcode2html($blog['post_content']);
		$post_content_short=bbcode2html($post_content_short);
	}
	if (!empty($config['use_smilies'])) {
		$blog['post_content']=text2smilies($blog['post_content']);
		$post_content_short=text2smilies($post_content_short);
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
	$fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'.inc.php',$towrite);

	$blog['post_content']=$post_content_short;
	$towrite='<?php $post='.var_export($blog,true).';';
	$fileop->file_put_contents(_CACHEPATH_.'/blogs/posts/'.$blog['post_id']{0}.'/'.$blog['post_id'].'_short.inc.php',$towrite);

	if (!isset($blog_details[$blog['fk_blog_id']])) {
		$query="SELECT `blog_id`,`blog_name`,`blog_diz`,`blog_skin`,`fk_user_id`,`alt_url` FROM `{$dbtable_prefix}user_blogs` WHERE `blog_id`=".$blog['fk_blog_id'];
		if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res2)) {
			$blog_details=mysql_fetch_assoc($res2);
			$blog_details['blog_name']=sanitize_and_format($blog_details['blog_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$blog_details['blog_diz']=sanitize_and_format($blog_details['blog_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		}
	}
	if (!is_dir(_CACHEPATH_.'/blogs/'.$blog_details['blog_id']{0}.'/'.$blog_details['blog_id'])) {
		$fileop->mkdir(_CACHEPATH_.'/blogs/'.$blog_details['blog_id']{0}.'/'.$blog_details['blog_id']);
	}
	$towrite='<?php $blog='.var_export($blog_details,true).';';
	$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$blog_details['blog_id']{0}.'/'.$blog_details['blog_id'].'/blog.inc.php',$towrite);

	$year=(int)date('Y',$last_approved);
	$month=(int)date('m',$last_approved);
	if (isset($blog_archive[$blog['fk_blog_id']][$year][$month])) {
		$blog_archive[$blog['fk_blog_id']][$year][$month]+=1;
	} else {
		$blog_archive[$blog['fk_blog_id']][$year][$month]=1;
	}
}

foreach ($blog_archive as $bid=>$archive) {
	krsort($archive,SORT_NUMERIC);
	$bid=(string)$bid;
	$towrite='<?php $blog_archive='.var_export($archive,true).';';
	$fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$bid{0}.'/'.$bid.'/blog_archive.inc.php',$towrite);
}

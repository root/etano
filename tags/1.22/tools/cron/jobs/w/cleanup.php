<?php
$jobs[]='clean_tmp';
$jobs[]='fix_stats';

function clean_tmp() {
	if ($dh=opendir(_BASEPATH_.'/tmp/admin')) {
		while (($file=readdir($dh))!==false) {
			if ($file{0}!='.' && $file!='index.html' && is_file(_BASEPATH_.'/tmp/admin/'.$file) && filectime(_BASEPATH_.'/tmp/admin/'.$file)<time()-172800) {	// 2 days
				@unlink(_BASEPATH_.'/tmp/admin/'.$file);
			}
		}
		closedir($dh);
	}

	if ($dh=opendir(_BASEPATH_.'/tmp')) {
		while (($file=readdir($dh))!==false) {
			if ($file{0}!='.' && $file!='index.html' && is_file(_BASEPATH_.'/tmp/'.$file) && filectime(_BASEPATH_.'/tmp/'.$file)<time()-172800) {	// 2 days
				@unlink(_BASEPATH_.'/tmp/'.$file);
			}
		}
		closedir($dh);
	}
}


function fix_stats() {
	global $dbtable_prefix;

	$sql="UPDATE `{$dbtable_prefix}blog_posts` a SET a.`stat_comments`=(SELECT count(*) FROM `{$dbtable_prefix}comments_blog` b WHERE a.`post_id`=b.`fk_parent_id`)";
	@mysql_query($sql);
	$sql="UPDATE `{$dbtable_prefix}user_blogs` a SET a.`stat_posts`=(SELECT count(*) FROM `{$dbtable_prefix}blog_posts` b WHERE a.`blog_id`=b.`fk_blog_id`)";
	@mysql_query($sql);
	$sql="UPDATE `{$dbtable_prefix}user_photos` a SET a.`stat_comments`=(SELECT count(*) FROM `{$dbtable_prefix}comments_photo` b WHERE a.`photo_id`=b.`fk_parent_id`)";
	@mysql_query($sql);
}

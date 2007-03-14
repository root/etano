<?
$jobs[]='gen_blogposts_cache';

function gen_blogposts_cache() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$config=get_site_option(array('bbcode_blogs'),'core');

	require_once _BASEPATH_.'/includes/classes/modman.class.php';
	$modman=new modman();

//	$query="SELECT `post_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted`,`fk_blog_id`,`title`,`post_content`,`stat_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `status`='".PSTAT_APPROVED."' AND `last_changed`>=DATE_SUB(now(),INTERVAL ".($interval+2)." MINUTE)";
	$query="SELECT `post_id`,UNIX_TIMESTAMP(`date_posted`) as `date_posted`,`fk_blog_id`,`title`,`post_content`,`stat_comments` FROM `{$dbtable_prefix}blog_posts` WHERE `status`='".PSTAT_APPROVED."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
		if ($config['bbcode_blogs']) {
			$rsrow['post_content']=bbcode2html($rsrow['post_content']);
		}

		$yearmonth=date('Ym',$rsrow['date_posted']);
		$posts=array();
		if (is_file(_CACHEPATH_.'/blogs/'.$rsrow['fk_blog_id'].'/'.$yearmonth.'.inc.php')) {
			include _CACHEPATH_.'/blogs/'.$rsrow['fk_blog_id'].'/'.$yearmonth.'.inc.php';
		}
		$posts[]=$rsrow;
		$towrite='<?php $posts='.var_export($posts,true).';';
		$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$rsrow['fk_blog_id'].'/'.$yearmonth.'.inc.php',$towrite);
	}
}

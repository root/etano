<?
$jobs[]='gen_blog_feeds';
$jobs[]='gen_comment_feeds';

function gen_blog_feeds() {
	global $dbtable_prefix;
	require_once _BASEPATH_.'/includes/access_levels.inc.php';

	$short_blog_chars=400;

	if (allow_at_level('read_blogs')) {	// if non-members are allowed to read blogs...
		require_once _BASEPATH_.'/includes/classes/rss_writer.class.php';
		$rss_writer_object=new rss_writer_class();
		$rss_writer_object->specification='1.0';
		$rss_writer_object->about=_BASEURL_.'/rss/latest-blogs.xml';
//		$rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';
		$properties=array();
		$properties['description']='Latest Blogs on '._SITENAME_;
		$properties['link']=_BASEURL_;
		$properties['title']='Latest Blogs';
//		$properties['dc:date']=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
		$rss_writer_object->addchannel($properties);

		$query="SELECT `post_id`,`alt_url`,UNIX_TIMESTAMP(`date_posted`) as `date_posted`,`title`,`post_content` FROM `{$dbtable_prefix}blog_posts` WHERE `status`=".STAT_APPROVED." AND `is_public`=1 ORDER BY `date_posted` DESC LIMIT 10";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$properties=array();
			if (strlen($rsrow['post_content'])<$short_blog_chars) {
				$properties['description']=$rsrow['post_content'];
			} else {
				$properties['description']=substr($rsrow['post_content'],0,strrpos(substr($rsrow['post_content'],0,$short_blog_chars),' '));
			}
			$properties['description']=bbcode2html(sanitize_and_format($properties['description'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]));
			if (empty($rsrow['alt_url'])) {
				$properties['link']=_BASEURL_.'/blog_post_view.php?pid='.$rsrow['post_id'];
			} else {
				$properties['link']=$rsrow['alt_url'];
			}
			$properties['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
//			$properties['dc:date']=$rsrow['date_posted'];
			$rss_writer_object->additem($properties);
		}
		if ($rss_writer_object->writerss($towrite)) {
			require_once _BASEPATH_.'/includes/classes/fileop.class.php';
			$fileop=new fileop();
			$fileop->file_put_contents(_BASEPATH_.'/rss/latest-blogs.xml',$towrite);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$rss_writer_object->error;
		}
	}
	return true;
}

function gen_comment_feeds() {
	global $dbtable_prefix;
	require_once _BASEPATH_.'/includes/access_levels.inc.php';

	$short_blog_chars=400;

	if (allow_at_level('read_blogs')) {	// if non-members are allowed to read blogs...
		require_once _BASEPATH_.'/includes/classes/rss_writer.class.php';
		$rss_writer_object=new rss_writer_class();
		$rss_writer_object->specification='1.0';
		$rss_writer_object->about=_BASEURL_.'/rss/latest-comments.xml';
//		$rss_writer_object->rssnamespaces['dc']='http://purl.org/dc/elements/1.1/';
		$properties=array();
		$properties['description']='Latest blog comments on '._SITENAME_;
		$properties['link']=_BASEURL_;
		$properties['title']='Latest Blog Comments';
//		$properties['dc:date']=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
		$rss_writer_object->addchannel($properties);

		$query="SELECT a.`comment_id`,a.`fk_user_id`,c.`alt_url` as `profile_url`,a.`_user`,a.`comment`,b.`post_id`,b.`title`,b.`alt_url` as `post_url` FROM `{$dbtable_prefix}blog_comments` a LEFT JOIN `{$dbtable_prefix}user_profiles` c ON a.`fk_user_id`=c.`fk_user_id`,`{$dbtable_prefix}blog_posts` b WHERE a.`fk_parent_id`=b.`post_id` AND a.`status`=".STAT_APPROVED." AND b.`is_public`=1 AND b.`status`=".STAT_APPROVED." ORDER BY a.`date_posted` DESC LIMIT 10";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$properties=array();
			if (strlen($rsrow['comment'])<$short_blog_chars) {
				$properties['description']=$rsrow['comment'];
			} else {
				$properties['description']=substr($rsrow['comment'],0,strrpos(substr($rsrow['comment'],0,$short_blog_chars),' '));
			}
			$properties['description']=sanitize_and_format($properties['description'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
			if (empty($rsrow['post_url'])) {
				$properties['link']=_BASEURL_.'/blog_post_view.php?pid='.$rsrow['post_id'].'#comm'.$rsrow['comment_id'];
			} else {
				$properties['link']=$rsrow['post_url'].'#comm'.$rsrow['comment_id'];
			}
			$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2DISPLAY]);
			$properties['title']=sprintf('%1$s on "%2$s"',$rsrow['_user'],$rsrow['title']);
//			$properties['dc:date']=$rsrow['date_posted'];
			$rss_writer_object->additem($properties);
		}
		if ($rss_writer_object->writerss($towrite)) {
			require_once _BASEPATH_.'/includes/classes/fileop.class.php';
			$fileop=new fileop();
			$fileop->file_put_contents(_BASEPATH_.'/rss/latest-comments.xml',$towrite);
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']=$rss_writer_object->error;
		}
	}
	return true;
}

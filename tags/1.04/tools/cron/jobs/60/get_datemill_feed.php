<?php
$jobs[]='get_datemill_feed';

function get_datemill_feed() {
	global $dbtable_prefix;
	require_once _BASEPATH_.'/includes/classes/feed_reader.class.php';
	$module_code='datemill_feed';
	$config=get_site_option(array('feed_url'),$module_code);
	if ($config['feed_url']) {
		$fr=new feedReader();
		$ok=$fr->getFeed($config['feed_url']);
		if ($ok) {
			$query="REPLACE INTO `{$dbtable_prefix}feed_cache` SET `module_code`='$module_code',`feed_xml`='".sanitize_and_format($fr->getRawXML(),TYPE_STRING,FORMAT_ADDSLASH)."',`update_time`='".gmdate('YmdHis')."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		} else {
			echo 'error retrieving the feed--> ';
		}
	}
	return true;
}

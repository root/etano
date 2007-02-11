<?php
$jobs[]='clear_search_cache';

function clear_search_cache() {
	$dbtable_prefix=$GLOBALS['dbtable_prefix'];
	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$query="DELETE FROM `{$dbtable_prefix}site_searches` WHERE `date_posted`<=now()-INTERVAL '$interval' MINUTE";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	return true;
}

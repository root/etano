<?php
/******************************************************************************
newdsb
===============================================================================
File:                       includes/general_functions.inc.php
$Revision: 75 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

// copy of the admin_functions.inc.php: get_site_option()
// make sure they're synchronized
function get_site_option($option,$module_code) {
	$myreturn=0;
	global $dbtable_prefix;
	$query="SELECT `config_option`,`config_value` FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code`='$module_code'";
	if (is_array($option)) {
		if (!empty($option)) {
			$query.=" AND `config_option` IN ('".join("','",$option)."')";
		}
	} else {
		$query.=" AND `config_option`='$option'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=array();
		while ($rsrow=mysql_fetch_row($res)) {
			$myreturn[$rsrow[0]]=$rsrow[1];
		}
		if (is_string($option)) {
			$myreturn=array_shift($myreturn);
		}
	}
	return $myreturn;
}


function get_module_codes_by_type($module_type) {
	$myreturn=array();
	global $dbtable_prefix;
	$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='$module_type'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn[]=mysql_result($res,$i,0);
	}
	return $myreturn;
}


// This function does NOT convert html to text.
// Make sure that the string is clean before calling this function
function bbcode2html($str) {
	$from=array('~\[url=(http://[^<">\(\)\[\]]*?)\](.*?)\[/url\]~','~\[b\](.*?)\[/b\]~','~\[u\](.*?)\[/u\]~','~\[quote\](.*?)\[/quote\]~','~\[img=(http://[^<">\(\)\[\]]*?)\]~');
	$to=array('<a target="_blank" rel="nofollow" href="$1">$2</a>','<strong>$1</strong>','<span class="underline">$1</span>','<blockquote>$1</blockquote>','<img src="$1" />');
	return preg_replace($from,$to,$str);
}


// wrapper for the create_pager2() function
function pager($totalrows,$offset,$results) {
	$lang_strings['page']='Pages:';					// translate this
	$lang_strings['rpp']='Results to show:';		// translate this
	$lang_strings['goto_first']='Go to first page';		// translate this
	$lang_strings['goto_last']='Go to last page';		// translate this
	$lang_strings['goto_next']='Go to next page';		// translate this
	$lang_strings['goto_prev']='Go to previous page';		// translate this
	return create_pager2($totalrows,$offset,$results,$lang_strings);
}

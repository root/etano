<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/blog_delete.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
check_login_member('write_blogs');

if (is_file(_BASEPATH_.'/events/processors/blog_delete.php')) {
	include _BASEPATH_.'/events/processors/blog_delete.php';
}

$topass=array();
$blog_id=isset($_GET['bid']) ? (int)$_GET['bid'] : 0;

$query="SELECT `post_id` FROM `{$dbtable_prefix}blog_posts` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_blog_id`=$blog_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$post_ids=array();
for ($i=0;$i<mysql_num_rows($res);++$i) {
	$post_ids[]=mysql_result($res,$i,0);
}
$query="DELETE FROM `{$dbtable_prefix}comments_blog` WHERE `fk_parent_id` IN ('".join("','",$post_ids)."')";
if (isset($_on_before_delete)) {
	for ($i=0;isset($_on_before_delete[$i]);++$i) {
		call_user_func($_on_before_delete[$i]);
	}
}
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}blog_posts` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `fk_blog_id`=$blog_id";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$query="DELETE FROM `{$dbtable_prefix}user_blogs` WHERE `blog_id`=$blog_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

$topass['message']['type']=MESSAGE_INFO;
$topass['message']['text']=$GLOBALS['_lang'][16];

$nextpage='my_blogs.php';
if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
}
if (isset($_on_after_delete)) {
	for ($i=0;isset($_on_after_delete[$i]);++$i) {
		call_user_func($_on_after_delete[$i]);
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

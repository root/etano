<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/comment_delete.php
$Revision: 327 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/comments.inc.php';
check_login_member('auth');

if (is_file(_BASEPATH_.'/events/processors/comment_delete.php')) {
	include_once _BASEPATH_.'/events/processors/comment_delete.php';
}

$topass=array();
$comment_id=isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;

if (!empty($comment_id)) {
	$comment_type=isset($_GET['comment_type']) ? $_GET['comment_type'] : '';

	switch ($comment_type) {

		case 'blog':
			$table="{$dbtable_prefix}blog_comments";
			$parent_table="{$dbtable_prefix}blog_posts";
			$parent_key="post_id";
			break;

		case 'photo':
			$table="{$dbtable_prefix}photo_comments";
			$parent_table="{$dbtable_prefix}user_photos";
			$parent_key="photo_id";
			break;

		case 'user':
			$table="{$dbtable_prefix}profile_comments";
			$parent_table="{$dbtable_prefix}user_profiles";
			$parent_key="fk_user_id";
			break;

	}
	$query="SELECT b.`fk_user_id` FROM `$table` a,`$parent_table` b WHERE a.`comment_id`=$comment_id AND a.`fk_parent_id`=b.`$parent_key`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
		// delete only if I am the owner of the original post this comment's been made on
		$query="DELETE FROM `$table` WHERE `comment_id`=$comment_id";
		if (isset($_on_before_delete)) {
			$GLOBALS['comment_ids']=array($comment_id);
			$GLOBALS['comment_type']=$comment_type;
			for ($i=0;isset($_on_before_delete[$i]);++$i) {
				call_user_func($_on_before_delete[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']=$GLOBALS['_lang'][30];
		if (isset($_on_after_delete)) {
			for ($i=0;isset($_on_after_delete[$i]);++$i) {
				call_user_func($_on_after_delete[$i]);
			}
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$GLOBALS['_lang'][31];
	}
}

if (!empty($_GET['return'])) {
	$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$nextpage=$input['return'];
} else {
	$nextpage='home.php';
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

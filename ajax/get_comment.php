<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/get_comment.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/comments.inc.php';

$output='';
if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && !empty($_POST['comment_id']) && !empty($_POST['t'])) {
	$comment_id=(int)$_POST['comment_id'];
	$table='';

	switch ($_POST['t']) {

		case 'blog':
			$table="{$dbtable_prefix}blog_comments";
			break;

		case 'photo':
			$table="{$dbtable_prefix}photo_comments";
			break;

		case 'user':
			$table="{$dbtable_prefix}profile_comments";
			break;

	}

	if (!empty($table)) {
		$query="SELECT `comment_id`,`comment` FROM `$table` WHERE `comment_id`=$comment_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' LIMIT 1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output=mysql_fetch_assoc($res);
			$output=sanitize_and_format($output,TYPE_STRING,FORMAT_RUENCODE);
			$output='0|'.$output['comment_id'].'|'.$output['comment'];
		} else {
			$output='1|'.$GLOBALS['_lang'][252];
		}
	}
} else {
	$output='1|'.$GLOBALS['_lang'][252];
}
echo $output;

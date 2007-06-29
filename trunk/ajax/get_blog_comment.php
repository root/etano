<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/get_blog_comment.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);

$output='';
if (isset($_SESSION['user']['user_id']) && !empty($_POST['comment_id'])) {
	$comment_id=(int)$_POST['comment_id'];
	$query="SELECT `comment_id`,`comment` FROM `{$dbtable_prefix}blog_comments` WHERE `comment_id`='$comment_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_row($res);
//		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT] | FORMAT_RUENCODE);
		// don't sanitize it cause it works without
		$output=sanitize_and_format($output,TYPE_STRING,FORMAT_RUENCODE);
		$output='0|'.join('|',$output);
	} else {
		$output='1|You are not allowed to edit this comment';
	}
} else {
	$output='1|You are not allowed to edit this comment';
}
echo $output;

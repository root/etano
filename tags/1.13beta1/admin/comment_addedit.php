<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/comment_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$output['m']=sanitize_and_format_gpc($_GET,'m',TYPE_STRING,0,'');

$default['defaults']=array();
if ($output['m']=='blog') {
	require _BASEPATH_.'/includes/tables/comments_blog.inc.php';
	$default=$comments_blog_default;
	$table="`{$dbtable_prefix}comments_blog`";
} elseif ($output['m']=='photo') {
	require _BASEPATH_.'/includes/tables/comments_photo.inc.php';
	$default=$comments_photo_default;
	$table="`{$dbtable_prefix}comments_photo`";
} elseif ($output['m']=='user') {
	require _BASEPATH_.'/includes/tables/comments_profile.inc.php';
	$default=$comments_profile_default;
	$table="`{$dbtable_prefix}comments_profile`";
}
$output=array_merge($output,$default['defaults']);

if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['comment_id'])) {
	$comment_id=(int)$_GET['comment_id'];
	$query="SELECT `comment_id`,`fk_parent_id`,`fk_user_id`,`_user`,`comment` FROM $table WHERE `comment_id`=$comment_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=array_merge($output,mysql_fetch_assoc($res));
		$output['comment']=sanitize_and_format($output['comment'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

$output['bbcode_comments']=get_site_option('bbcode_comments','core');
if (empty($output['bbcode_comments'])) {
	unset($output['bbcode_comments']);
}

if (empty($output['return'])) {
	// because of the GET, our 'return' is decoded
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}
$tpl->set_file('content','comment_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']='Edit Comment';
$tplvars['page']='comment_addedit';
$tplvars['css']='comment_addedit.css';
include 'frame.php';

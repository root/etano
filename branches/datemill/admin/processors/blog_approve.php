<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/blog_approve.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/triggers.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
if (!empty($_REQUEST['search'])) {
	$input['search']=sanitize_and_format_gpc($_REQUEST,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$query="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$input['search']."' AND `search_type`=".SEARCH_BLOG;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$results=mysql_result($res,0,0);
		$input['pids']=explode(',',$results);
	}
} elseif (!empty($_REQUEST['pids'])) {
	$input['pids']=sanitize_and_format($_REQUEST['pids'],TYPE_INT,0,array());
}
$input['return']=sanitize_and_format_gpc($_REQUEST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

if (!empty($input['pids'])) {
	$query="UPDATE `{$dbtable_prefix}blog_posts` SET `status`=".STAT_APPROVED.",`reject_reason`='',`last_changed`='".gmdate('YmdHis')."' WHERE `post_id` IN ('".join("','",$input['pids'])."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	on_approve_blog_post($input['pids']);

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Blog post(s) approved. They will appear on site as soon as the cache is generated';
}

if (!empty($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
} else {
	$nextpage=_BASEURL_.'/admin/member_search.php';
}
redirect2page($nextpage,$topass,'',true);

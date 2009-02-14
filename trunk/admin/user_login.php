<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_login.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN | DEPT_MODERATOR);

$output['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$tpl=new phemplate('skin/','remove_nonjs');

if (isset($_GET['top'])) {
	$content_file='user_login_top.html';
	$tpl->set_var('user',$_SESSION[_LICENSE_KEY_]['user']);
} else {
	$content_file='user_login.html';
	if (strpos($output['return'],'?')===false) {
		$qs_sep='?';
	} else {
		$qs_sep='&';
	}
	$output['return'].=$qs_sep.'clean_user_session=1';
	$output['return']=rawurlencode($output['return']);
	$query="SELECT a.`".USER_ACCOUNT_ID."` as `user_id`,b.`_user` as `user`,a.`status`,a.`membership`,UNIX_TIMESTAMP(a.`last_activity`) as `last_activity`,a.`email`,b.`status` as `pstat` FROM `".USER_ACCOUNTS_TABLE."` a LEFT JOIN `{$dbtable_prefix}user_profiles` b ON a.`".USER_ACCOUNT_ID."`=b.`fk_user_id` WHERE a.`".USER_ACCOUNT_ID."`=".$output['uid'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user=mysql_fetch_assoc($res);
		$user['prefs']=get_user_settings($user['user_id'],'def_user_prefs',array('date_format','datetime_format','time_offset','rate_my_photos','profile_comments'));
		$_SESSION[_LICENSE_KEY_]['user']=$user;
	}
}

$tpl->set_file('content',$content_file);
$tpl->set_var('output',$output);
echo $tpl->process('','content');

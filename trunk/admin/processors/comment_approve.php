<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/comment_approve.php
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
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$input=array();
$input['cids']=sanitize_and_format_gpc($_GET,'cids',TYPE_INT,0,array());
$input['m']=sanitize_and_format_gpc($_GET,'m',TYPE_STRING,0,'');
$input['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

if (!empty($input['cids']) && !empty($input['m'])) {
	switch ($input['m']) {

		case 'blog':
			$table="`{$dbtable_prefix}blog_comments`";
			break;

	 	case 'photo':
			$table="`{$dbtable_prefix}photo_comments`";
			break;

		case 'user':
			$table="`{$dbtable_prefix}profile_comments`";
			break;

	}
	$query="UPDATE $table SET `status`=".STAT_APPROVED.",`last_changed`='".gmdate('YmdHis')."' WHERE `comment_id` IN ('".join("','",$input['cids'])."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (is_file(_BASEPATH_.'/events/processors/comment_addedit.php')) {
		include_once _BASEPATH_.'/events/processors/comment_addedit.php';
		if (isset($_on_after_approve)) {
			$GLOBALS['comment_ids']=$input['cids'];
			$GLOBALS['comment_type']=$input['m'];
			for ($i=0;isset($_on_after_approve[$i]);++$i) {
				call_user_func($_on_after_approve[$i]);
			}
		}
	}

	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='Comment(s) approved.';
}

if (!empty($input['return'])) {
	$nextpage=_BASEURL_.'/admin/'.$input['return'];
} else {
	$nextpage=_BASEURL_.'/admin/comment_search.php';
}
redirect2page($nextpage,$topass,'',true);

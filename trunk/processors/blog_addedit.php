<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/blog_addedit.php
$Revision: 67 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/user_functions.inc.php';
require_once '../includes/tables/user_blogs.inc.php';
check_login_member('write_blogs');

if (is_file(_BASEPATH_.'/events/processors/blog_addedit.php')) {
	include_once _BASEPATH_.'/events/processors/blog_addedit.php';
}

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='my_blogs.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($user_blogs_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$user_blogs_default['defaults'][$k]);
	}
	$input['fk_user_id']=$_SESSION['user']['user_id'];
	if (isset($_POST['return']) && !empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

// check for input errors
	if (empty($input['blog_name'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the title of the blog';
	}

	if (!$error) {
		$input['blog_name']=remove_banned_words($input['blog_name']);
		$input['blog_diz']=remove_banned_words($input['blog_diz']);
		require_once '../includes/classes/modman.class.php';
		$modman=new modman();
		$towrite=array();	// what to write in the cache file
		if (!empty($input['blog_id'])) {
			foreach ($input as $k=>$v) {
				$towrite[$k]=sanitize_and_format_gpc($_POST,$k,TYPE_STRING,$__field2format[TEXT_GPC2DISPLAY],'');
			}
			$query="UPDATE IGNORE `{$dbtable_prefix}user_blogs` SET ";
			foreach ($user_blogs_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `blog_id`='".$input['blog_id']."' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
			if (isset($_on_before_update)) {
				for ($i=0;isset($_on_before_update[$i]);++$i) {
					eval($_on_before_update[$i].'();');
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Blog details changed.';     // translate
			$input['blog_id']=(string)$input['blog_id'];
			if (isset($_on_after_update)) {
				for ($i=0;isset($_on_after_update[$i]);++$i) {
					eval($_on_after_update[$i].'();');
				}
			}
		} else {
			unset($input['blog_id']);
			foreach ($input as $k=>$v) {
				$towrite[$k]=sanitize_and_format_gpc($_POST,$k,TYPE_STRING,$__field2format[TEXT_GPC2DISPLAY],'');
			}
			$query="INSERT INTO `{$dbtable_prefix}user_blogs` SET ";
			foreach ($user_blogs_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (isset($_on_before_insert)) {
				for ($i=0;isset($_on_before_insert[$i]);++$i) {
					eval($_on_before_insert[$i].'();');
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['blog_id']=mysql_insert_id();
			$towrite['blog_id']=$input['blog_id'];
			$input['blog_id']=(string)$input['blog_id'];

			// create the blog cache folder if it doesn't exist
			if (!is_dir(_CACHEPATH_.'/blogs/'.$input['blog_id']{0}.'/'.$input['blog_id'])) {
				$modman->fileop->mkdir(_CACHEPATH_.'/blogs/'.$input['blog_id']{0}.'/'.$input['blog_id']);
			}
			$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$input['blog_id']{0}.'/'.$input['blog_id'].'/blog_archive.inc.php','<?php $blog_archive=array();');
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Blog created.';     // translate
			if (isset($_on_after_insert)) {
				for ($i=0;isset($_on_after_insert[$i]);++$i) {
					eval($_on_after_insert[$i].'();');
				}
			}
		}

		$towrite='<?php $blog='.var_export($towrite,true).';';
		$modman->fileop->file_put_contents(_CACHEPATH_.'/blogs/'.$input['blog_id']{0}.'/'.$input['blog_id'].'/blog.inc.php',$towrite);
	} else {
		$nextpage='blog_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['return']=rawurlencode($input['return']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				eval($_on_error[$i].'();');
			}
		}
	}
}
$nextpage=_BASEURL_.'/'.$nextpage;
redirect2page($nextpage,$topass,'',true);
?>
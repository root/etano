<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/banned_words_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/tables/banned_words.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='admin/banned_words.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	foreach ($banned_words_default['types'] as $k=>$v) {
		$input[$k]=sanitize_and_format_gpc($_POST,$k,$__field2type[$v],$__field2format[$v],$banned_words_default['defaults'][$k]);
	}

// check for input errors
	if (empty($input['word'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter the word';
		$input['error_flirt']='red_border';
	}

	if (!$error) {
		if (!empty($input['word_id'])) {
			$query="UPDATE `{$dbtable_prefix}banned_words` SET ";
			foreach ($banned_words_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			$query.=" WHERE `word_id`='".$input['word_id']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Word changed.';
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Word not changed. Perhaps this word already exists?';
			}
		} else {
			$query="INSERT INTO `{$dbtable_prefix}banned_words` SET ";
			foreach ($banned_words_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.="`$k`='".$input[$k]."',";
				}
			}
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_affected_rows()) {
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Word added.';
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Word not added. Perhaps this word already exists?';
			}
		}

		if (!$error) {
			// save in file
			require_once _BASEPATH_.'/includes/classes/modman.class.php';
			$query="SELECT `word` FROM `{$dbtable_prefix}banned_words`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$towrite[]=mysql_result($res,$i,0);
			}
			$towrite='<?php $_banned_words='.var_export($towrite,true).';';
			$modman=new modman();
			$modman->fileop->file_put_contents(_BASEPATH_.'/includes/banned_words.inc.php',$towrite);
		}
	} else {
		$nextpage='admin/banned_words.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
redirect2page($nextpage,$topass,$qs);
?>
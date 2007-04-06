<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/processors/popup_lang_string.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../../includes/sessions.inc.php';
require_once '../../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../../includes/classes/phemplate.class.php';
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$query="SELECT `module_code` FROM `{$dbtable_prefix}modules` WHERE `module_type`='".MODULE_SKIN."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}
	$input['lang_strings']=sanitize_and_format_gpc($_POST,'lang_strings',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
	$input['lk_id']=(int)$_POST['lk_id'];

	for ($i=0;isset($skins[$i]);++$i) {
		if (!isset($input['lang_strings'][$skins[$i]]) || empty($input['lang_strings'][$skins[$i]])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Please set the string for all skins.';
			break;
		}
	}

	if (!$error) {
		for ($i=0;isset($skins[$i]);++$i) {
			$query="SELECT `ls_id` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id`='".$input['lk_id']."' AND `skin`='".$skins[$i]."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$query="UPDATE `{$dbtable_prefix}lang_strings` SET `lang_value`='".$input['lang_strings'][$skins[$i]]."' WHERE `fk_lk_id`='".$input['lk_id']."' AND `skin`='".$skins[$i]."'";
			} else {
				$query="INSERT INTO `{$dbtable_prefix}lang_strings` SET `fk_lk_id`='".$input['lk_id']."',`skin`='".$skins[$i]."',`lang_value`='".$input['lang_strings'][$skins[$i]]."'";
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}

		regenerate_langstrings_array();

		$_SESSION['topass']['message']['type']=MESSAGE_INFO;
		$_SESSION['topass']['message']['text']='String translated';
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
		redirect2page('admin/popup_lang_string.php',$topass);
	}
}
?>
<html>
<body>
<script type="text/javascript">
	opener.document.location=opener.document.location;
	window.close();
</script>
</body>
</html>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       processors/popup_save_search.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/user_functions.inc.php';
check_login_member('save_searches');

$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='popup_save_search.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['search']=sanitize_and_format_gpc($_POST,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['title']=sanitize_and_format_gpc($_POST,'title',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

	if (empty($input['search'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='No search selected.';	// translate this
	}
	if (empty($input['title'])) {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter a title for this search.';	// translate this
	}

	if (!$error) {
		$query="SELECT `search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$input['search']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$ser_search=mysql_result($res,0,0);
			$search=unserialize($ser_search);
			unset($search['acclevel_id']);
			$query="INSERT INTO `{$dbtable_prefix}user_searches` (`fk_user_id`,`title`,`search_qs`,`search`,`alert`) VALUES ('".$_SESSION['user']['user_id']."','".$input['title']."','".array2qs($search)."','$ser_search',1)";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Search saved.';
		}
	} else {
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

if (!isset($_POST['silent'])) {
	if (!$error) {
		$_SESSION['topass']=$topass;
		?>
		<html>
		<body>
		<script type="text/javascript">
			opener.document.location=opener.document.location;
			window.close();
		</script>
		</body>
		</html>
		<?php
	} else {
		redirect2page($nextpage,$topass,$qs);
	}
} else {
	echo $topass['message']['text'];
	die;
}
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       ajax/save_baseurl.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once dirname(__FILE__).'/../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once dirname(__FILE__).'/../includes/user_functions.inc.php';
check_login_member('auth');

$error=false;
$output='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
	$input['baseurl']=sanitize_and_format($_POST['baseurl'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
	$input['lk']=sanitize_and_format($_POST['lk'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);

	if (empty($input['baseurl']) || $input['baseurl']=='http://') {
		$error=true;
		$output='Please enter the url of the site.';
	}

	if (!$error) {
		$temp=@parse_url($input['baseurl']);
		if (!$temp || empty($temp['scheme']) || $temp['scheme']!='http' || !empty($temp['query']) || !empty($temp['fragment'])) {
			$error=true;
			$output='Invalid url specified. The url must start with http:// and contain a valid web site address.';
		}
		if (!$error && $temp['host']==gethostbyname($temp['host'])) {
			$error=true;
			$output='Could not resolve host name. Please re-check the entered url address.';
		}
	}

	if (!$error) {
		$query="UPDATE `user_sites` SET `baseurl`='".$input['baseurl']."' WHERE `license`='".$input['lk']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_affected_rows()) {
			$output=1;
		} else {
			$output='Invalid license.';
		}
	}
}
echo $output;

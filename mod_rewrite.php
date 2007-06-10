<?php
/******************************************************************************
newdsb
===============================================================================
File:                       mod_rewrite.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';

$request_uri=$_SERVER['REQUEST_URI'];

$baseurl_parts=explode('/',substr(_BASEURL_,7));
if (($pos=strpos(_BASEURL_,'/',8))!==false) {
	$request_uri=str_replace(substr(_BASEURL_,$pos),'',$request_uri);
}
if ($request_uri{0}=='/') {
	$request_uri=substr($request_uri,1);
}
if ($request_uri{strlen($request_uri)-1}=='/') {
	$request_uri=substr($request_uri,0,-1);
}

//phpinfo();
//print $request_uri;die;
$uri_parts=explode('/',$request_uri);

if ($uri_parts[0]=='blog' && isset($uri_parts[1])) {
	$_GET['pid']=$uri_parts[1];
	require_once 'blog_post_view.php';
} elseif ($uri_parts[0]=='browse') {

} elseif (!empty($uri_parts[0]) && !isset($uri_parts[1])) {
	$_GET['user']=$uri_parts[0];
	require_once 'profile.php';
	die;
} else {
	redirect2page('index.php');
}
?>
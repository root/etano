<?php
/******************************************************************************
Etano
===============================================================================
File:                       mod_rewrite.php
$Revision: 207 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';

$request_uri=$_SERVER['REQUEST_URI'];
if (($pos=strpos($request_uri,'?'))!==false) {
	$request_uri=substr($request_uri,0,$pos);
}
//phpinfo();die;

if (($pos=strpos(_BASEURL_,'/',8))!==false) {
	$request_uri=str_replace(substr(_BASEURL_,$pos),'',$request_uri);
}
$request_uri=trim($request_uri,'/');
$uri_parts=explode('/',$request_uri);

if ($uri_parts[0]=='blogpost' && isset($uri_parts[1])) {
	$_GET['pid']=$uri_parts[1];
	require_once 'blog_post_view.php';
	die;
} elseif ($uri_parts[0]=='blog' && isset($uri_parts[1])) {
	$_GET['bid']=$uri_parts[1];
	require_once 'blog_view.php';
	die;
} elseif ($uri_parts[0]=='devblog' && isset($uri_parts[1]) && $uri_parts[1]=='community-builder') {
	$_GET['st']='new';
	require_once 'blog_search.php';
	die;
} elseif ($uri_parts[0]=='inbox') {
	require_once 'mailbox.php';
	die;
} elseif ($uri_parts[0]=='photo' && isset($uri_parts[1])) {
	$_GET['photo_id']=$uri_parts[1];
	require_once 'photo_view.php';
	die;
} elseif ($uri_parts[0]=='kb') {
	if (isset($uri_parts[1])) {
		$_GET['kbc_id']=$uri_parts[1];
	} else {
		$_GET['kbc_id']=0;
	}
	require_once 'support.php';
	die;
} else {
	redirect2page('index.php');
}

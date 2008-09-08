<?php
/******************************************************************************
Etano
===============================================================================
File:                       blog_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/tables/user_blogs.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
check_login_member('write_blogs');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$output=$user_blogs_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	// our 'return' here was decoded in the processor
	$output['return2']=$output['return'];
	$output['return']=rawurlencode($output['return']);
} elseif (!empty($_GET['bid'])) {
	$blog_id=(int)$_GET['bid'];
	$query="SELECT `blog_id`,`blog_name`,`blog_diz` FROM `{$dbtable_prefix}user_blogs` WHERE `blog_id`=$blog_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$output=sanitize_and_format($output,TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	}
}

if (empty($output['return'])) {
	$output['return2']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$output['return']=rawurlencode($output['return2']);
}
$tpl->set_file('content','blog_addedit.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][220];
$tplvars['page_title']=$GLOBALS['_lang'][220];
$tplvars['page']='blog_addedit';
$tplvars['css']='blog_addedit.css';
if (is_file('blog_addedit_left.php')) {
	include 'blog_addedit_left.php';
}
include 'frame.php';

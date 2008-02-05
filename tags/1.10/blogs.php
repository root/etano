<?php
/******************************************************************************
Etano
===============================================================================
File:                       blogs.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
check_login_member('all');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output['lang_271']=sanitize_and_format($GLOBALS['_lang'][271],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$tpl->set_file('content','blogs.html');
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][202];
$tplvars['page_title']=$GLOBALS['_lang'][202];
$tplvars['page']='blogs';
$tplvars['css']='blogs.css';
if (is_file('blogs_left.php')) {
	include 'blogs_left.php';
}
include 'frame.php';

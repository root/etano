<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_blogs.php
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
check_login_member('write_blogs');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$from="`{$dbtable_prefix}user_blogs`";
$where="`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";

$loop=array();
$query="SELECT `blog_id`,`blog_name`,`blog_diz`,`stat_posts` FROM $from WHERE $where ORDER BY `blog_name`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$loop[]=$rsrow;
}
$loop=sanitize_and_format($loop,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_262']=sanitize_and_format($GLOBALS['_lang'][262],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$output['return2me']='my_blogs.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_blogs.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][239];
$tplvars['page_title']=$GLOBALS['_lang'][239];
$tplvars['page']='my_blogs';
$tplvars['css']='my_blogs.css';
if (is_file('my_blogs_left.php')) {
	include 'my_blogs_left.php';
}
include 'frame.php';

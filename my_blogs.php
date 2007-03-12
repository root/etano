<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_blogs.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(10);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');
$output=array();

$from="`{$dbtable_prefix}user_blogs`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

$loop=array();
$query="SELECT `blog_id`,`blog_name`,`blog_diz`,`stat_posts` FROM $from WHERE $where ORDER BY `blog_name`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$loop[]=$rsrow;
}
$loop=sanitize_and_format($loop,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);

$output['return']='my_blogs.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','my_blogs.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Blog posts';
$tplvars['page_title']='My Blogs';
$tplvars['page']='my_blogs';
$tplvars['css']='my_blogs.css';
if (is_file('my_blogs_left.php')) {
	include 'my_blogs_left.php';
}
include 'frame.php';
?>
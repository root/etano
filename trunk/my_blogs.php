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

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;

$from="`{$dbtable_prefix}user_blogs`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	$query="SELECT `blog_id`,`blog_name`,`blog_diz`,`num_posts` FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$loop=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$loop[]=$rsrow;
	}
	$loop=sanitize_and_format($loop,TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$tpl->set_file('content','my_blogs.html');
$tpl->set_loop('loop',$loop);
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
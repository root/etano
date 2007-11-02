<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_blog_posts.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/blog_posts.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/blogs.inc.php';
check_login_member('write_blogs');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$output['blog_name']='';
if (!empty($_GET['bid'])) {
	$output['fk_blog_id']=(int)$_GET['bid'];
	$where="a.`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND a.`fk_blog_id`=".$output['fk_blog_id']." AND a.`fk_blog_id`=b.`blog_id`";
	$from="`{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_blogs` b";

	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);

	$loop=array();
	if (!empty($totalrows)) {
		if ($o>=$totalrows) {
			$o=$totalrows-$r;
			$o=$o>=0 ? $o : 0;
		}
		$query="SELECT a.`post_id`,a.`title`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,b.`blog_name` FROM $from WHERE $where ORDER BY a.`date_posted` DESC LIMIT $o,$r";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['date_format'],$rsrow['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
			$loop[]=$rsrow;
		}
		$loop=sanitize_and_format($loop,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$output['pager2']=pager($totalrows,$o,$r);
		$output['blog_name']=$loop[0]['blog_name'];
	} else {
		// get just the name of the blog
		$query="SELECT `blog_name` FROM `{$dbtable_prefix}user_blogs` WHERE `blog_id`=".$output['fk_blog_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$output['blog_name']=mysql_result($res,0,0);
		}
	}

	$output['return2me']='my_blog_posts.php';
	if (!empty($_SERVER['QUERY_STRING'])) {
		$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
	}
	$output['return2me']=rawurlencode($output['return2me']);
	$tpl->set_file('content','my_blog_posts.html');
	$tpl->set_loop('loop',$loop);
	$tpl->set_var('output',$output);
	$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
	$tpl->drop_loop('loop');
	unset($loop);
}

$tplvars['title']=$GLOBALS['_lang'][109];
$tplvars['page_title']=sprintf($GLOBALS['_lang'][238],$output['blog_name']);
$tplvars['page']='my_blog_posts';
$tplvars['css']='my_blog_posts.css';
if (is_file('my_blog_posts_left.php')) {
	include 'my_blog_posts_left.php';
}
include 'frame.php';

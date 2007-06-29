<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/blog_results.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$input=array();
$post_ids=array();
$do_query=true;
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_BLOG;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$post_ids=mysql_result($res,0,0);
		$post_ids=explode(',',$post_ids);
		$input=unserialize(mysql_result($res,0,1));	// sanitized already
	}
	if (!isset($_GET['refresh'])) {
		$do_query=false;
	}
} else {
	// first search here, no cache, must calculate everything
	$input['stat']=sanitize_and_format_gpc($_GET,'stat',TYPE_INT,0,0);
	if (empty($input['stat'])) {
		unset($input['stat']);
	}
	$input['flagged']=sanitize_and_format_gpc($_GET,'flagged',TYPE_INT,0,0);
	if (empty($input['flagged'])) {
		unset($input['flagged']);
	}
	$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
	if (empty($input['uid'])) {
		unset($input['uid']);
	}
	$input['fk_blog_id']=sanitize_and_format_gpc($_GET,'fk_blog_id',TYPE_INT,0,0);
	if (empty($input['fk_blog_id'])) {
		unset($input['fk_blog_id']);
	}
}

if ($do_query) {
	$where='1';
	$from="`{$dbtable_prefix}blog_posts` a";

	if (isset($input['stat'])) {
		$where.=" AND a.`status`='".$input['stat']."'";
	}
	if (isset($input['flagged'])) {
		$where.=" AND a.`flagged`>0";
	}
	if (isset($input['uid'])) {	// a user's blog posts
		$where.=" AND a.`fk_user_id`=".$input['uid'];
	}
	if (isset($input['fk_blog_id'])) {	// a blog's posts
		$where.=" AND a.`fk_blog_id`=".$input['fk_blog_id'];
	}

	$query="SELECT a.`post_id` FROM $from WHERE $where";
//print $query;
//die;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$post_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$post_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$output['search_md5']=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_BLOG.",`search`='$serialized_input',`results`='".join(',',$post_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($post_ids);

// get the details for the found blog_ids...unfortunately that's another query
$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$post_ids=array_slice($post_ids,$o,$r);
	$query="SELECT a.`post_id`,UNIX_TIMESTAMP(a.`date_posted`) as `date_posted`,a.`fk_user_id`,a.`_user`,a.`fk_blog_id`,b.`blog_name`,a.`title`,a.`status`,a.`stat_views`,a.`stat_comments` FROM `{$dbtable_prefix}blog_posts` a,`{$dbtable_prefix}user_blogs` b WHERE a.`post_id` IN ('".join("','",$post_ids)."') AND a.`fk_blog_id`=b.`blog_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['status']==STAT_PENDING) {
			$rsrow['pending']=true;
		} elseif ($rsrow['status']==STAT_EDIT) {
			$rsrow['need_edit']=true;
		} elseif ($rsrow['status']==STAT_APPROVED) {
			$rsrow['approved']=true;
		}
		$loop[]=$rsrow;
	}

	$_GET=array('search'=>$output['search_md5']);
	$output['pager2']=pager($totalrows,$o,$r);
	$output['totalrows']=$totalrows;
}

if (empty($loop)) {
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='No blogs found meeting your search criteria.';
	redirect2page('admin/blog_search.php',$topass);
}

$output['return2me']='blog_results.php';
if (!empty($output['search_md5'])) {
	$output['return2me'].='?search='.$output['search_md5'];
} elseif (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','blog_results.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Blog Search Results';
$tplvars['page']='blog_results';
$tplvars['css']='blog_results.css';
include 'frame.php';
?>
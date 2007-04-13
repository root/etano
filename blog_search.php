<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blog_search.php
$Revision: 98 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$output['o']=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$output['r']=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

$input=array();
$post_ids=array();
$error=false;
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_BLOG;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$post_ids=mysql_result($res,0,0);
		$post_ids=explode(',',$post_ids);
		$input=unserialize(mysql_result($res,0,1));	// sanitized already
		check_login_member($input['acclevel_id']);
	}
} else {
	// first search here, no cache, must calculate everything
	$input['acclevel_id']=17; // default access level is the one for advanced search!!!!
	$select="a.`post_id`";
	$from="`{$dbtable_prefix}blog_posts` a";
	$where="a.`is_public`=1 AND a.`status`='".STAT_APPROVED."'";
	$orderby="a.`date_posted` DESC";

	// define here all search types
	// you can either add fields to be read into $search_fields or build the query directly
	if (isset($_GET['st'])) {
		$input['st']=$_GET['st'];
		switch ($_GET['st']) {

			case 'new':
				//$orderby="a.`date_posted` DESC";	// default
				break;

			case 'views':
				$input['acclevel_id']=17;
				$orderby="a.`stat_views` DESC";
				break;

			case 'comm':
				$input['acclevel_id']=17;
				$orderby="a.`stat_comments` DESC";
				break;

			case 'tag':
				$input['acclevel_id']=17;
				$input['tags']=sanitize_and_format_gpc($_GET,'tags',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
				// remove extra spaces and words with less than 3 chars
				$input['tags']=trim(preg_replace(array("/[^a-zA-Z0-9']/","/\b.{1,3}\b/","/\s\s+/"),array(' ','',' '),$input['tags']));
				if (!empty($input['tags'])) {
					$select.=",MATCH (a.`title`,a.`post_content`) AGAINST ('".$input['tags']."' IN BOOLEAN MODE) as `match_score`";
					$where.=" AND MATCH (a.`title`,a.`post_content`) AGAINST ('".$input['tags']."' IN BOOLEAN MODE)";
					$orderby="`match_score` DESC";
				} else {
					$error=true;
				}
				break;

			default:
				break;

		}
	}
	check_login_member($input['acclevel_id']);

	if (!$error) {
		$query="SELECT $select FROM $from WHERE $where ORDER BY $orderby";
//print $query;
//die;
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$post_ids[]=$rsrow['post_id'];
		}
		$serialized_input=serialize($input);
		$output['search_md5']=md5($serialized_input);
		$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_BLOG.",`search`='$serialized_input',`results`='".join(',',$post_ids)."'";
		if (isset($_SESSION['user']['user_id'])) {
			$query.=",`fk_user_id`='".$_SESSION['user']['user_id']."'";
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	}
}
$output['totalrows']=count($post_ids);

// get the results from user cache for the found post_ids
$results=array();
if (!empty($output['totalrows'])) {
	$post_ids=array_slice($post_ids,$output['o'],$output['r']);
	require_once _BASEPATH_.'/includes/classes/blog_cache.class.php';
	$blog_cache=new blog_cache(get_my_skin());
	$loop=$blog_cache->get_cache_beta($post_ids,'result_blog','tpl');

	// set $_GET for the pager.
	$_GET=array('search'=>$output['search_md5']);
	$output['pager2']=pager($output['totalrows'],$output['o'],$output['r']);
}

$tpl->set_file('content','blog_search.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');

$tplvars['title']='Search Results';
$tplvars['page_title']='Search Results';
$tplvars['page']='blog_search';
$tplvars['css']='blog_search.css.php';
if (is_file('blog_search_left.php')) {
	include 'blog_search_left.php';
}
include 'frame.php';
?>
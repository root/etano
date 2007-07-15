<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/photo_results.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$input=array();
$photo_ids=array();
$do_query=true;
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_PHOTO;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$photo_ids=mysql_result($res,0,0);
		$photo_ids=explode(',',$photo_ids);
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
	$input['is_main']=sanitize_and_format_gpc($_GET,'is_main',TYPE_INT,0,0);
	if (empty($input['is_main'])) {
		unset($input['is_main']);
	}
	$input['is_private']=sanitize_and_format_gpc($_GET,'is_private',TYPE_INT,0,0);
	if (empty($input['is_private'])) {
		unset($input['is_private']);
	}
	$input['caption']=sanitize_and_format_gpc($_GET,'caption',TYPE_INT,0,0);
	if (empty($input['caption'])) {
		unset($input['caption']);
	}
	$input['flagged']=sanitize_and_format_gpc($_GET,'flagged',TYPE_INT,0,0);
	if (empty($input['flagged'])) {
		unset($input['flagged']);
	}
	$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
	if (empty($input['uid'])) {
		unset($input['uid']);
	}
}

if ($do_query) {
	$where='1';
	$from="`{$dbtable_prefix}user_photos` a";

	if (isset($input['stat'])) {
		$where.=" AND a.`status`='".$input['stat']."'";
	}
	if (isset($input['is_main'])) {
		$where.=" AND a.`is_main`=1";
	}
	if (isset($input['is_private'])) {
		$where.=" AND a.`is_private`=1";
	}
	if (isset($input['caption'])) {
		$where.=" AND a.`caption`<>''";
	}
	if (isset($input['flagged'])) {
		$where.=" AND a.`flagged`=1";
	}
	if (isset($input['uid'])) {	// a user's photos
		$where.=" AND a.`fk_user_id`=".$input['uid'];
	}

	$query="SELECT a.`photo_id` FROM $from WHERE $where";
//print $query;
//die;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$photo_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$output['search_md5']=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_PHOTO.",`search`='$serialized_input',`results`='".join(',',$photo_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($photo_ids);

// get the details for the found photo_ids...unfortunately that's another query
$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$photo_ids=array_slice($photo_ids,$o,$r);
	$query="SELECT *,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['is_private']=empty($rsrow['is_private']) ? 'public' : 'private';
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
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
	$topass['message']['text']='No photos found meeting your search criteria.';
	redirect2page('admin/photo_search.php',$topass);
}

$output['pic_width']=get_site_option('pic_width','core_photo');

$output['return2me']='photo_results.php';
if (!empty($output['search_md5'])) {
	$output['return2me'].='?search='.$output['search_md5'];
} elseif (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','photo_results.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Photo Search Results';
$tplvars['page']='photo_results';
$tplvars['css']='photo_results.css';
include 'frame.php';

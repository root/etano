<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/photo_results.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : _RESULTS_;
$search_md5=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__html2format[_HTML_TEXTFIELD_],'');

$input=array();
$photo_ids=array();
$do_query=true;
if (!empty($search_md5)) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='$search_md5' AND `search_type`="._SEARCH_PHOTO_;
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
	if (isset($input['uid'])) {	// a user's photos
		$where.=" AND a.`fk_user_id`=".$input['uid'];
	}

	$query="SELECT `photo_id` FROM $from WHERE $where";
//print $query;
//die;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$photo_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$photo_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$search_md5=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='$search_md5',`search_type`="._SEARCH_PHOTO_.",`search`='$serialized_input',`results`='".join(',',$photo_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($photo_ids);

// get the details for the found user_ids...unfortunately that's another query
$photos=array();
if (!empty($totalrows)) {
	$photo_ids=array_slice($photo_ids,$o,$r);
	$query="SELECT *,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM `{$dbtable_prefix}user_photos` WHERE `photo_id` IN ('".join("','",$photo_ids)."')";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['is_private']=empty($rsrow['is_private']) ? 'public' : 'private';
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		if ($rsrow['status']==PSTAT_PENDING) {
			$rsrow['pending']=true;
		} elseif ($rsrow['status']==PSTAT_EDIT) {
			$rsrow['need_edit']=true;
		} elseif ($rsrow['status']==PSTAT_APPROVED) {
			$rsrow['approved']=true;
		}
		$photos[]=$rsrow;
	}

	$_GET=array('search'=>$search_md5);
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
	$tpl->set_var('totalrows',$totalrows);
}

$tplvars['pic_width']=get_site_option('pic_width','core_photo');
$return=rawurlencode('photo_results.php?search='.$search_md5.'&o='.$o.'&r='.$r);

$tpl->set_file('content','photo_results.html');
$tpl->set_loop('photos',$photos);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('search_md5',$search_md5);
$tpl->set_var('return',$return);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('photos');

$tplvars['title']='Photo Search Results';
include 'frame.php';
?>
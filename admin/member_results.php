<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/member_results.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();

$sorts=array('a.`_user`','a.`score` DESC','a.`fk_user_id` DESC');
$sort_names=array('alphabetically','by score (highest first)','newest first');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
$sortby=(isset($_GET['sortby']) && isset($sorts[(int)$_GET['sortby']])) ? (int)$_GET['sortby'] : 0;
$output['sortby']=vector2options($sort_names,$sortby);

$input=array();
$user_ids=array();
$do_query=true;
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_USER;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($user_ids,$input)=mysql_fetch_row($res);
		$user_ids=explode(',',$user_ids);
		$input=unserialize($input);	// sanitized already
	}
	if (!isset($_GET['refresh'])) {
		$do_query=false;
	}
} else {
	// first search here, no cache, must calculate everything
	$input['user']=sanitize_and_format_gpc($_GET,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (empty($input['user'])) {
		unset($input['user']);
	}
	$input['email']=sanitize_and_format_gpc($_GET,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	if (empty($input['email'])) {
		unset($input['email']);
	}
	$input['astat']=sanitize_and_format_gpc($_GET,'astat',TYPE_INT,0,0);
	if (empty($input['astat'])) {
		unset($input['astat']);
	}
	$input['pstat']=sanitize_and_format_gpc($_GET,'pstat',TYPE_INT,0,0);
	if (empty($input['pstat'])) {
		unset($input['pstat']);
	}
	$input['membership']=sanitize_and_format_gpc($_GET,'membership',TYPE_INT,0,0);
	if (empty($input['membership'])) {
		unset($input['membership']);
	}
	$input['photo']=sanitize_and_format_gpc($_GET,'photo',TYPE_INT,0,0);
	if (empty($input['photo'])) {
		unset($input['photo']);
	}
	$input['album']=sanitize_and_format_gpc($_GET,'album',TYPE_INT,0,0);
	if (empty($input['album'])) {
		unset($input['album']);
	}
}

// we build the query but run it only if this is a first run, otherwise we already know the results
// we need the query though for the md5
$where="a.`fk_user_id`=b.`".USER_ACCOUNT_ID."`";
$from="`{$dbtable_prefix}user_profiles` a,`".USER_ACCOUNTS_TABLE."` b";

if (isset($input['user'])) {
	$where.=" AND a.`_user` LIKE '".$input['user']."%'";
}
if (isset($input['pstat'])) {	// profile status
	$where.=" AND a.`status`=".$input['pstat'];
}
if (isset($input['astat'])) {	// account status
	$where.=" AND b.`status`=".$input['astat'];
}
if (isset($input['membership'])) {
	$where.=" AND b.`membership`=".$input['membership'];
}
if (isset($input['email'])) {
	$where.=" AND b.`email`='".$input['email']."'";
}
if (isset($input['photo'])) {
	if ($input['photo']==1) {	// only members with main photo
		$where.=" AND a.`_photo`<>''";
	} elseif ($input['photo']==-1) {	// only members without main photo
		$where.=" AND a.`_photo`=''";
	}
}
if (isset($input['album'])) {	// only members with photo album
	$where.=" AND a.`fk_user_id`=c.`fk_user_id` GROUP BY a.`fk_user_id`";
	$from.=",`{$dbtable_prefix}user_photos` c";
}

// continue building the where clause of the query based on the input values we have.
for ($i=0;isset($basic_search_fields[$i]);++$i) {
	$field=&$_pfields[$basic_search_fields[$i]];
	$field->search()->set_value($_GET,true);
	$where.=$field->search()->query_search();
	$input=array_merge($input,$field->search()->get_value(true));
} // the for() that constructs the where

$query="SELECT a.`fk_user_id` FROM $from WHERE $where";
//print $query;die;
$new_md5=md5($query);
if ($output['search_md5']!=$new_md5) {
	$output['search_md5']=$new_md5;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=mysql_real_escape_string(serialize($input));
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($user_ids);

// get the details for the found user_ids...unfortunately that's another query
$loop=array();
if (!empty($totalrows)) {
	if ($o>=$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	// handle prev/next from profile.php
	if (isset($_GET['uid']) && isset($_GET['go']) && ($_GET['go']==1 || $_GET['go']==-1)) {
		$uid=(int)$_GET['uid'];
		$key=array_search($uid,$user_ids)+$_GET['go'];
		if (isset($user_ids[$key])) {
			$uid=(int)$user_ids[$key];
			redirect2page('admin/profile.php',array(),'uid='.$uid.'&search='.$output['search_md5']);
		}
	}
	$query="SELECT a.`fk_user_id`,a.`_user`,a.`_photo`,a.`status`,a.`del`";
	foreach ($_pfields as $k=>&$field) {
		$query.=','.$field->query_select();
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` a WHERE a.`fk_user_id` IN ('".join("','",$user_ids)."') ORDER BY ".$sorts[$sortby]." LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		foreach ($_pfields as $k=>&$field) {
			$field->set_value($rsrow,false);
			$rsrow[$field->config['dbfield'].'_label']=$field->config['label'];
			$rsrow[$field->config['dbfield']]=$field->display();
		}
		if (empty($rsrow['_photo']) || !is_file(_BASEPATH_.'/media/pics/t1/'.$rsrow['_photo']) || !is_file(_BASEPATH_.'/media/pics/t2/'.$rsrow['_photo']) || !is_file(_BASEPATH_.'/media/pics/'.$rsrow['_photo'])) {
			$rsrow['_photo']='no_photo.gif';
		}
		if ($rsrow['status']==STAT_PENDING) {
			$rsrow['pending']=true;
		} elseif ($rsrow['status']==STAT_EDIT) {
			$rsrow['need_edit']=true;
		} elseif ($rsrow['status']==STAT_APPROVED) {
			$rsrow['approved']=true;
		}
		if (empty($rsrow['del'])) {
			unset($rsrow['del']);
		}
		$loop[]=$rsrow;
	}

	$_GET=array('search'=>$output['search_md5'],'sortby'=>$sortby);
	$output['pager2']=pager($totalrows,$o,$r);
	$output['totalrows']=$totalrows;
}

if (empty($loop)) {
	$topass['message']['type']=MESSAGE_INFO;
	$topass['message']['text']='No members found meeting your search criteria.';
	redirect2page('admin/member_search.php',$topass);
}

$output['return2me']='member_results.php';
if (!empty($output['search_md5'])) {
	$output['return2me'].='?search='.$output['search_md5']."&sortby={$sortby}&o={$o}&r={$r}";
} elseif (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','member_results.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Search Results';
$tplvars['css']='member_results.css';
$tplvars['page']='member_results';
include 'frame.php';

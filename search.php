<?php
/******************************************************************************
Etano
===============================================================================
File:                       search.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

/* TODO: it would be awsome if we could see which are the most popular searches and refresh them from cron */

//define('CACHE_LIMITER','private');
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$input=array();
$user_ids=array();
$got_from_cache=false;	// this flag is used to tell if we went thru the query cache or not.
$skip_cache=false;	// we want to retrieve the most up to date results. Obviously this refers to the second cache lookup, not to the
					// one here. We don't cache these results either.

if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_USER;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$got_from_cache=true;
		list($user_ids,$input)=mysql_fetch_row($res);
		$user_ids=explode(',',$user_ids);
		$input=unserialize($input);	// sanitized already
		check_login_member($input['acclevel_code']);
	}
}

if (!$got_from_cache) {
	// THIS PART IS DUPLICATED IN CRON/d/5SEARCH_ALERTS.PHP. IF YOU CHANGE THIS YOU MUST CHANGE THAT TOO.
	// first search here, no cache, must calculate everything
	$input['acclevel_code']='search_advanced'; // default access level is the one for advanced search!!!!
	$search_fields=array();
	$continue=false;	// for searches not based on search_fields
	$select="a.`fk_user_id`";
	$from="`{$dbtable_prefix}user_profiles` a";
	$where=' a.`status`='.STAT_APPROVED.' AND a.`del`=0';
	$orderby="ORDER BY a.`score` DESC";

	// define here all search types
	// you can either add fields to be read into $search_fields or build the query directly
	if (isset($_GET['st'])) {
		$input['st']=$_GET['st'];
		switch ($_GET['st']) {

			case 'basic':
				$input['acclevel_code']='search_basic';
				$search_fields=$basic_search_fields;
				if (isset($_GET['wphoto'])) {
					$where.=" AND a.`_photo`!=''";
				}
				break;

			case 'adv':
				$input['acclevel_code']='search_advanced';
				// for advanced search we get all fields
				foreach ($_pfields as $field_id=>$field) {
					if (!empty($field->config['searchable'])) {
						$search_fields[]=$field_id;
					}
				}
				if (isset($_GET['wphoto'])) {
					$where.=" AND a.`_photo`!=''";
				}
				break;

			case 'user':
				$input['acclevel_code']='search_advanced';
				$continue=true;
				$input['user']=sanitize_and_format_gpc($_GET,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
				if (strlen($input['user'])<=3) {
					$topass['message']['text']=$GLOBALS['_lang'][8];
					$topass['message']['type']=MESSAGE_ERROR;
					$where='';	// force no results returned.
				} else {
					$where.=" AND a.`_user` LIKE '".$input['user']."%'";
				}
				break;

			case 'net':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$input['fk_user_id']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
				$input['fk_net_id']=sanitize_and_format_gpc($_GET,'nid',TYPE_INT,0,0);
				$select="b.`fk_user_id_other`";
				$from="`{$dbtable_prefix}user_networks` b,".$from;
				$where="b.`fk_user_id`=".$input['fk_user_id']." AND b.`fk_net_id`=".$input['fk_net_id']." AND b.`nconn_status`=1 AND b.`fk_user_id_other`=a.`fk_user_id` AND ".$where;
				break;

			case 'new':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$orderby="ORDER BY a.`date_added` DESC";
				break;

			case 'online':
				$input['acclevel_code']='search_basic';
				$continue=true;
				$where.=" AND a.`fk_user_id` IN ('".join("','",array_keys($_list_of_online_members))."')";
				$skip_cache=true;
				break;

			case 'vote':
			case 'views':
			case 'comm':
// TODO
				break;

			default:
				break;

		}
	}
	check_login_member($input['acclevel_code']);

	for ($i=0;isset($search_fields[$i]);++$i) {
		$field=&$_pfields[$search_fields[$i]];
		$field->search()->set_value($_GET,true);
		$where.=$field->search()->query_search();
		$input=array_merge($input,$field->search()->get_value(true));
	}
	if (!empty($where)) {	// if $where is empty then a condition above prevents us from searching. There must be a message to display.
		$serialized_input=mysql_real_escape_string(serialize($input));
		$query="SELECT $select FROM $from WHERE $where $orderby";
//print $query;die;
		$output['search_md5']=md5($query);
		if (!$skip_cache) {
			// let's give the cache one more chance. This is useful for the first page of results when we didn't know the search_md5 until now
			$query2="SELECT `results` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_USER;
			if (!($res=@mysql_query($query2))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$user_ids=mysql_result($res,0,0);
				$user_ids=explode(',',$user_ids);
				$got_from_cache=true;
			}
		}
		if (!$got_from_cache) {	// this is where we absolutely must search the users...it's the most expensive search
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$user_ids[]=mysql_result($res,$i,0);
			}
			$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
				$query.=",`fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	}
}
$output['totalrows']=count($user_ids);

// get the results from user cache for the found user_ids
if (!empty($output['totalrows'])) {
	if ($o>=$output['totalrows']) {
		$o=$output['totalrows']-$r;
	}
	$user_ids=array_slice($user_ids,$o,$r);
	// last activity only for not online members
	$temp=array();
	$inject_by_uid=array();
	for ($i=0;isset($user_ids[$i]);++$i) {
		if (!isset($_list_of_online_members[$user_ids[$i]])) {
			$temp[]=$user_ids[$i];
		} else {
			$inject_by_uid[$user_ids[$i]]=array('last_online'=>$GLOBALS['_lang'][153]);
		}
	}
	if (!empty($temp)) {
		$time=mktime(gmdate('H'),gmdate('i'),gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'));
		$query="SELECT `".USER_ACCOUNT_ID."` as `uid`,UNIX_TIMESTAMP(`last_activity`) as `last_activity` FROM `".USER_ACCOUNTS_TABLE."` WHERE `".USER_ACCOUNT_ID."` IN ('".join("','",$temp)."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['last_activity']=$time-$rsrow['last_activity'];
			if ($rsrow['last_activity']<86400) {
				$inject_by_uid[$rsrow['uid']]=array('last_online'=>$GLOBALS['_lang'][154]);
			} elseif ($rsrow['last_activity']<172800) {
				$inject_by_uid[$rsrow['uid']]=array('last_online'=>$GLOBALS['_lang'][155]);
			} elseif ($rsrow['last_activity']<604800) {
				$inject_by_uid[$rsrow['uid']]=array('last_online'=>$GLOBALS['_lang'][156]);
			} elseif ($rsrow['last_activity']<2419200) {
				$inject_by_uid[$rsrow['uid']]=array('last_online'=>$GLOBALS['_lang'][157]);
			} else {
				$inject_by_uid[$rsrow['uid']]=array('last_online'=>$GLOBALS['_lang'][158]);
			}
		}
	}
	// how to display the results: in gallery mode or in list mode
	$rv_mode='list_view';
	if (isset($_COOKIE['sco_app']['rv_mode']) && $_COOKIE['sco_app']['rv_mode']=='g') {
		$rv_mode='gallery_view';
	} elseif (isset($_GET['v']) && ($_GET['v']=='g' || $_GET['v']=='l')) {
		setcookie('sco_app[rv_mode]',$_GET['v'],mktime(0,0,0,date('m'),date('d'),date('Y')+1),'/',$cookie_domain);
		if ($_GET['v']=='g') {
			$rv_mode='gallery_view';
		}
	}

	$cell_css_classes=array();
	for ($i=0;isset($user_ids[$i]);++$i) {
		if (isset($_list_of_online_members[$user_ids[$i]])) {
			$cell_css_classes[$i]='is_online';
		}
	}
	require _BASEPATH_.'/includes/classes/user_cache.class.php';
	$user_cache=new user_cache();
	$temp=$user_cache->get_cache_array($user_ids,'result_user',$inject_by_uid);
	if (!empty($temp)) {
		$output['results']=smart_table($temp,5,$rv_mode,$cell_css_classes);
		$output['pager2']=pager($output['totalrows'],$o,$r);
	} else {
		unset($output['totalrows']);
	}
	unset($user_cache,$temp);

	if (!$skip_cache) {
		// set $_GET for the pager.
		$_GET=array('search'=>$output['search_md5'],'v'=>!empty($_GET['v']) ? $_GET['v'] : 'l');
	}
} else {
	unset($output['totalrows']);
}
$output['lang_253']=sanitize_and_format($GLOBALS['_lang'][253],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
$output['lang_254']=sanitize_and_format($GLOBALS['_lang'][254],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$tpl->set_file('content','search.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_OPTIONAL);
$tpl->drop_var('output.results');
$tpl->drop_var('output.pager2');
unset($output['results'],$output['pager2']);

$tplvars['title']=$GLOBALS['_lang'][107];
$tplvars['page_title']=$GLOBALS['_lang'][107];
$tplvars['page']='search';
$tplvars['css']='search.css.php';
if (is_file('search_left.php')) {
	include 'search_left.php';
}
include 'frame.php';

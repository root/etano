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
require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';

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
		$user_ids=mysql_result($res,0,0);
		$user_ids=explode(',',$user_ids);
		$input=unserialize(mysql_result($res,0,1));	// sanitized already
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
	$where='1';
//	if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
//		$where.=" AND a.`fk_user_id`<>'".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
//	}
	$where.=' AND a.`status`='.STAT_APPROVED.' AND a.`del`=0';
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
					$where.=" AND a.`_photo`<>''";
				}
				break;

			case 'adv':
				$input['acclevel_code']='search_advanced';
				// for advanced search we get all fields
				foreach ($_pfields as $field_id=>$field) {
					if (isset($field['searchable'])) {
						$search_fields[]=$field_id;
					}
				}
				if (isset($_GET['wphoto'])) {
					$where.=" AND a.`_photo`<>''";
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
				$where=" a.`fk_user_id` IN ('".join("','",array_keys($_list_of_online_members))."') AND ".$where;
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
		$field=$_pfields[$search_fields[$i]];
		switch ($field['search_type']) {

			case FIELD_SELECT:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (!empty($input[$field['dbfield']])) {
					if ($field['field_type']==FIELD_SELECT) {
						$where.=" AND `".$field['dbfield']."`=".$input[$field['dbfield']];
					} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
						$where.=" AND `".$field['dbfield']."` LIKE '%|".$input[$field['dbfield']]."|%'";
					}
//				} else {
//					unset($input[$field['dbfield']]);
				}
				break;

			case FIELD_CHECKBOX_LARGE:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (!empty($input[$field['dbfield']])) {
					if ($field['field_type']==FIELD_SELECT) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="`".$field['dbfield']."`=".$input[$field['dbfield']][$j]." OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="`".$field['dbfield']."` LIKE '%|".$input[$field['dbfield']][$j]."|%' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					}
				} else {
					unset($input[$field['dbfield']]);
				}
				break;

			case FIELD_RANGE:
				$input[$field['dbfield'].'_min']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_min',TYPE_INT,0,0);
				$input[$field['dbfield'].'_max']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_max',TYPE_INT,0,0);
				$now=gmdate('YmdHis');
				if (!empty($input[$field['dbfield'].'_max'])) {
					if ($field['field_type']==FIELD_DATE) {
						$where.=" AND `".$field['dbfield']."`>=DATE_SUB('$now',INTERVAL ".$input[$field['dbfield'].'_max']." YEAR)";
					} elseif ($field['field_type']==FIELD_SELECT) {
						$where.=" AND `".$field['dbfield']."`<=".$input[$field['dbfield'].'_max'];
					}
				} else {
					unset($input[$field['dbfield'].'_max']);
				}
				if (!empty($input[$field['dbfield'].'_min'])) {
					if ($field['field_type']==FIELD_DATE) {
						$where.=" AND `".$field['dbfield']."`<=DATE_SUB('$now',INTERVAL ".$input[$field['dbfield'].'_min']." YEAR)";
					} elseif ($field['field_type']==FIELD_SELECT) {
						$where.=" AND `".$field['dbfield']."`>=".$input[$field['dbfield'].'_min'];
					}
				} else {
					unset($input[$field['dbfield'].'_min']);
				}
				break;

			case FIELD_LOCATION:
				$input[$field['dbfield'].'_country']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_country',TYPE_INT,0,0);
				if (!empty($input[$field['dbfield'].'_country'])) {
					$where.=" AND `".$field['dbfield']."_country`=".$input[$field['dbfield'].'_country'];
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`=".$input[$field['dbfield'].'_country'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						list($prefered_input,$num_states)=mysql_fetch_row($res);
						if ($prefered_input=='s' && !empty($num_states)) {
							$input[$field['dbfield'].'_state']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_state',TYPE_INT,0,0);
							if (!empty($input[$field['dbfield'].'_state'])) {
								$where.=" AND `".$field['dbfield']."_state`=".$input[$field['dbfield'].'_state'];
								$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`=".$input[$field['dbfield'].'_state'];
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									$input[$field['dbfield'].'_city']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_city',TYPE_INT,0,0);
									if (!empty($input[$field['dbfield'].'_city'])) {
										$where.=" AND `".$field['dbfield']."_city`=".$input[$field['dbfield'].'_city'];
//									} else {
//										unset($input[$field['dbfield'].'_city']);
									}
								}
//							} else {
//								unset($input[$field['dbfield'].'_state']);
							}
						} elseif ($prefered_input=='z') {
							$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_zip',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
							$input[$field['dbfield'].'_dist']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_dist',TYPE_INT,0,0);
							if (!empty($input[$field['dbfield'].'_zip']) && !empty($input[$field['dbfield'].'_dist'])) {
								$query="SELECT `rad_latitude`,`rad_longitude` FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$input[$field['dbfield'].'_zip']."'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									list($rad_latitude,$rad_longitude)=mysql_fetch_row($res);
									// WE USE ONLY MILES HERE. IF YOU WANT KM YOU NEED TO CONVERT MILES TO KM
									// earth radius=3956 miles =6367 km; 3956*2=7912
									// Haversine Formula: (more exact for small distances)
									$where.=" AND a.`rad_latitude`<>-a.`rad_longitude` AND asin(sqrt(pow(sin((".(float)$rad_latitude."-a.`rad_latitude`)/2),2)+cos(".(float)$rad_latitude.")*cos(a.`rad_latitude`)*pow(sin((".(float)$rad_longitude."-a.`rad_longitude`)/2),2)))<=".(((int)$input[$field['dbfield'].'_dist'])/7912);
									// Law of Cosines for Spherical Trigonometry; 60*1.1515=69.09; 1.1515 miles in a degree
//									$where.=" AND DEGREES(ACOS(SIN(".(float)$rad_latitude.")*SIN(a.`rad_latitude`)+COS(".(float)$rad_latitude.")*COS(a.`rad_latitude`)*COS(".(float)$rad_longitude."-a.`rad_longitude`)))<=".(int)$input[$field['dbfield'].'_dist']/69.09;
								} else {
// should not return any result or at least warn the member that the zip code was not found.
								}
//							} else {
//								unset($input[$field['dbfield'].'_zip'],$input[$field['dbfield'].'_dist']);
							}
						}
					}
//				} else {
//					unset($input[$field['dbfield'].'_country']);
				}	// if (!empty($input[$field['dbfield'].'_country']))
				break;

		}	//switch ($field['search_type'])
	} // the for() that constructs the where

	if (!empty($where)) {	// if $where is empty then a condition above prevents us from searching. There must be a message to display.
		$serialized_input=mysql_real_escape_string(serialize($input));
		$query="SELECT $select FROM $from WHERE $where $orderby";
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
	//print $query;die;
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			for ($i=0;$i<mysql_num_rows($res);++$i) {
				$user_ids[]=mysql_result($res,$i,0);
			}
			$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id'])) {
				$query.=",`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
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
	require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
	$user_cache=new user_cache(get_my_skin());
	$output['results']=smart_table($user_cache->get_cache_array($user_ids,'result_user',$inject_by_uid),5,$rv_mode,$cell_css_classes);
	unset($user_cache);

	if (!$skip_cache) {
		// set $_GET for the pager.
		$_GET=array('search'=>$output['search_md5'],'v'=>!empty($_GET['v']) ? $_GET['v'] : 'l');
	}
	$output['pager2']=pager($output['totalrows'],$o,$r);
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

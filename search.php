<?php
/******************************************************************************
newdsb
===============================================================================
File:                       search.php
$Revision$
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

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$output['o']=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$output['r']=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

$input=array();
$user_ids=array();
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_USER;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user_ids=mysql_result($res,0,0);
		$user_ids=explode(',',$user_ids);
		$input=unserialize(mysql_result($res,0,1));	// sanitized already
		check_login_member($input['acclevel_id']);
	}
} else {
	// first search here, no cache, must calculate everything
	$input['acclevel_id']=17; // default access level is the one for advanced search!!!!
	if (isset($_GET['st'])) {
		$input['st']=$_GET['st'];
		switch ($_GET['st']) {

			case 'basic':
				$input['acclevel_id']=16;
				break;

			case 'adv':
				$input['acclevel_id']=17;
				break;

		}
	}
	check_login_member($input['acclevel_id']);

	$where='`status`='.STAT_APPROVED;
	$from="`{$dbtable_prefix}user_profiles` a";

	$search_fields=array();
	if ($input['acclevel_id']==17) {	// for advanced search we get all fields
		foreach ($_pfields as $field_id=>$field) {
			if (isset($field['searchable'])) {
				$search_fields[]=$field_id;
			}
		}
	} elseif ($input['acclevel_id']==16) {
		$search_fields=$default_search_fields;
	} else {
		$search_fields=$default_search_fields;
	}

	for ($i=0;isset($search_fields[$i]);++$i) {
		$field=$_pfields[$search_fields[$i]];
		switch ($field['search_type']) {

			case HTML_SELECT:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (!empty($input[$field['dbfield']])) {
					if ($field['html_type']==HTML_SELECT) {
						$where.=" AND `".$field['dbfield']."`='".$input[$field['dbfield']]."'";
					} elseif ($field['html_type']==HTML_CHECKBOX_LARGE) {
						$where.=" AND `".$field['dbfield']."` LIKE '%|".$input[$field['dbfield']]."|%'";
					}
				} else {
					unset($input[$field['dbfield']]);
				}
				break;

			case HTML_CHECKBOX_LARGE:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (!empty($input[$field['dbfield']])) {
					if ($field['html_type']==HTML_SELECT) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="`".$field['dbfield']."`='".$input[$field['dbfield']][$j]."' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					} elseif ($field['html_type']==HTML_CHECKBOX_LARGE) {
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

			case HTML_DATE:
				$input[$field['dbfield'].'_min']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_min',TYPE_INT,0,0);
				$input[$field['dbfield'].'_max']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_max',TYPE_INT,0,0);
				if (!empty($input[$field['dbfield'].'_max'])) {
					$where.=" AND `".$field['dbfield']."`>=DATE_SUB(now(),INTERVAL ".$input[$field['dbfield'].'_max']." YEAR)";
				} else {
					unset($input[$field['dbfield'].'_max']);
				}
				if (!empty($input[$field['dbfield'].'_min'])) {
					$where.=" AND `".$field['dbfield']."`<=DATE_SUB(now(),INTERVAL ".$input[$field['dbfield'].'_min']." YEAR)";
				} else {
					unset($input[$field['dbfield'].'_min']);
				}
				break;

			case HTML_LOCATION:
				$input[$field['dbfield'].'_country']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_country',TYPE_INT,0,0);
				if (!empty($input[$field['dbfield'].'_country'])) {
					$where.=" AND `".$field['dbfield']."_country`='".$input[$field['dbfield'].'_country']."'";
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$input[$field['dbfield'].'_country']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						list($prefered_input,$num_states)=mysql_fetch_row($res);
						if ($prefered_input=='s' && !empty($num_states)) {
							$input[$field['dbfield'].'_state']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_state',TYPE_INT,0,0);
							if (!empty($input[$field['dbfield'].'_state'])) {
								$where.=" AND `".$field['dbfield']."_state`='".$input[$field['dbfield'].'_state']."'";
								$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`='".$input[$field['dbfield'].'_state']."'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									$input[$field['dbfield'].'_city']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_city',TYPE_INT,0,0);
									if (!empty($input[$field['dbfield'].'_city'])) {
										$where.=" AND `".$field['dbfield']."_city`='".$input[$field['dbfield'].'_city']."'";
									} else {
										unset($input[$field['dbfield'].'_city']);
									}
								}
							} else {
								unset($input[$field['dbfield'].'_state']);
							}
						} elseif ($prefered_input=='z') {
							$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_zip',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
							$input[$field['dbfield'].'_dist']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_dist',TYPE_INT,0,0);
							if (!empty($input[$field['dbfield'].'_zip']) && !empty($input[$field['dbfield'].'_dist'])) {
								$query="SELECT RADIANS(`latitude`),RADIANS(`longitude`) FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$input[$field['dbfield'].'_zip']."'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									list($latitude,$longitude)=mysql_fetch_row($res);
									// earth radius=3956 miles =6367 km; 3956*2=7912
									// Haversine Formula: (more exact for small distances)
									$where.=" AND (`latitude`+`longitude`)<>0 AND (7912*asin(sqrt(pow(sin((".(float)$latitude."-RADIANS(`latitude`))/2),2)+cos(".(float)$latitude.")*cos(RADIANS(`latitude`))*pow(sin((".(float)$longitude."-RADIANS(`longitude`))/2),2))))<=".(int)$input[$field['dbfield'].'_dist'];
									// Law of Cosines for Spherical Trigonometry; 60*1.1515=69.09; 1.1515 miles in a degree
		//							$where.=" AND (69.09*DEGREES(ACOS(SIN(".(float)$latitude.")*SIN(RADIANS(`latitude`))+COS(".(float)$latitude.")*COS(RADIANS(`latitude`))*COS(".(float)$longitude."-RADIANS(`longitude`)))))<=".(int)$input[$field['dbfield'].'_dist'];
								} else {
		// should not return any result or at least warn the member that the zip code was not found.
								}
							} else {
								unset($input[$field['dbfield'].'_zip'],$input[$field['dbfield'].'_dist']);
							}
						}
					}
				} else {
					unset($input[$field['dbfield'].'_country']);
				}	// if (!empty($input[$field['dbfield'].'_country']))
				break;

		}	//switch ($field['search_type'])
	} // the for() that constructs the where

	$query="SELECT `fk_user_id` FROM $from WHERE $where ORDER BY a.`score` DESC";

//print $query;
//die;

	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$output['search_md5']=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
	if (isset($_SESSION['user']['user_id'])) {
		$query.=",`fk_user_id`='".$_SESSION['user']['user_id']."'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($user_ids);

// get the results from user cache for the found user_ids
$results=array();
if (!empty($totalrows)) {
	$user_ids=array_slice($user_ids,$output['o'],$output['r']);
	require_once _BASEPATH_.'/includes/classes/user_cache.class.php';
	$user_cache=new user_cache(get_my_skin());
//	$results=$user_cache->get_cache_array($user_ids,'user_gallery');
	$results=$user_cache->get_cache_array($user_ids,'user_list');
	$tpl->set_var('results',smart_table($results,5,'gallery_view'));
//	$tpl->set_var('results',smart_table($results,5,'list_view'));


/*
	if (isset($_GET['v']) && $_GET['v']=='g') {
		$results=$user_cache->get_cache_array($user_ids,'user_gallery');
		$tpl->set_var('results',smart_table($results,2,'gallery_view'));
	} else {
		for ($i=0;isset($user_ids[$i]);++$i) {
			$results[$i]['user_list']=$user_cache->get_cache($user_ids[$i],'user_list');
		}
		$tpl->set_loop('results',$results);
		$tpl->set_var('use_loop',true);
	}
*/
	// set $_GET for the pager.
	$_GET=array('search'=>$output['search_md5'],'v'=>(isset($_GET['v']) && !empty($_GET['v'])) ? $_GET['v'] : 'l');
	$tpl->set_var('pager2',pager($totalrows,$output['o'],$output['r']));
	$tpl->set_var('totalrows',$totalrows);
}

$tpl->set_file('content','search.html');
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);
$tpl->drop_var('results');

$tplvars['title']='Search Results';
$tplvars['page_title']='Search Results';
$tplvars['page']='search';
$tplvars['css']='search.css.php';
if (is_file('search_left.php')) {
	include 'search_left.php';
}
include 'frame.php';
?>
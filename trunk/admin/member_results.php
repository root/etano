<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/member_results.php
$Revision: 29 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);
$search_md5=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');

$input=array();
$user_ids=array();
$do_query=true;
if (!empty($search_md5)) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='$search_md5' AND `search_type`=".SEARCH_USER;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$user_ids=mysql_result($res,0,0);
		$user_ids=explode(',',$user_ids);
		$input=unserialize(mysql_result($res,0,1));	// sanitized already
	}
	if (!isset($_GET['refresh'])) {
		$do_query=false;
	}
} else {
	// first search here, no cache, must calculate everything
	$input['user']=sanitize_and_format_gpc($_GET,'user',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
	if (empty($input['user'])) {
		unset($input['user']);
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

	$search_fields=array();
	foreach ($_pfields as $field_id=>$field) {
		if (isset($field['searchable'])) {
			$search_fields[]=$field_id;
			if (count($search_fields)==RELEVANT_FIELDS) {
				break;
			}
		}
	}

	// see what fields we received from the search
	for ($i=0;isset($search_fields[$i]);++$i) {
		$field=$_pfields[$search_fields[$i]];
		switch ($field['search_type']) {

			case HTML_SELECT:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (empty($input[$field['dbfield']])) {
					unset($input[$field['dbfield']]);
				}
				break;

			case HTML_CHECKBOX_LARGE:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (empty($input[$field['dbfield']])) {
					unset($input[$field['dbfield']]);
				}
				break;

			case HTML_DATE:
				$input[$field['dbfield'].'_min']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_min',TYPE_INT,0,0);
				$input[$field['dbfield'].'_max']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_max',TYPE_INT,0,0);
				if (empty($input[$field['dbfield'].'_max'])) {
					unset($input[$field['dbfield'].'_max']);
				}
				if (empty($input[$field['dbfield'].'_min'])) {
					unset($input[$field['dbfield'].'_min']);
				}
				break;

			case HTML_LOCATION:
				$input[$field['dbfield'].'_country']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_country',TYPE_INT,0,0);
				if (!empty($input[$field['dbfield'].'_country'])) {
					$input[$field['dbfield'].'_state']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_state',TYPE_INT,0,0);
					if (!empty($input[$field['dbfield'].'_state'])) {
						$input[$field['dbfield'].'_city']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_city',TYPE_INT,0,0);
						if (empty($input[$field['dbfield'].'_city'])) {
							unset($input[$field['dbfield'].'_city']);
						}
					} else {
						unset($input[$field['dbfield'].'_state']);
					}
					$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_zip',TYPE_STRING,$__html2format[HTML_TEXTFIELD],'');
					$input[$field['dbfield'].'_dist']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_dist',TYPE_INT,0,0);
					if (empty($input[$field['dbfield'].'_zip']) || empty($input[$field['dbfield'].'_dist'])) {
						unset($input[$field['dbfield'].'_zip'],$input[$field['dbfield'].'_dist']);
					}
				} else {
					unset($input[$field['dbfield'].'_country']);
				}
				break;

		}	//switch ($field['search_type'])
	} // the for() that constructs the where
}

if ($do_query) {
	$where='1';
	$from="`{$dbtable_prefix}user_profiles` a";

	if (isset($input['user'])) {
		$where.=" AND a.`_user` LIKE '".$input['user']."%'";
	}
	if (isset($input['pstat'])) {	// profile status
		$where.=" AND a.`status`='".$input['pstat']."'";
	}
	if (isset($input['astat'])) {	// account status
		$where.=" AND a.`fk_user_id`=b.`user_id` AND b.`status`='".$input['astat']."'";
		$from.=",".USER_ACCOUNTS_TABLE." b";
	}
	if (isset($input['membership'])) {
		$where.=" AND b.`membership`='".$input['membership']."'";
		if (!isset($input['astatus'])) {
			$where.=" AND a.`fk_user_id`=b.`user_id`";
			$from.=",".USER_ACCOUNTS_TABLE." b";
		}
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

	if (empty($search_fields)) {
		foreach ($_pfields as $field_id=>$field) {
			if (isset($field['searchable'])) {
				$search_fields[]=$field_id;
				if (count($search_fields)==RELEVANT_FIELDS) {
					break;
				}
			}
		}
	}

	// continue building the where clause of the query based on the input values we have.
	for ($i=0;isset($search_fields[$i]);++$i) {
		$field=$_pfields[$search_fields[$i]];
		switch ($field['search_type']) {

			case HTML_SELECT:
				if (isset($input[$field['dbfield']])) {
					if ($field['html_type']==HTML_SELECT) {
						$where.=" AND a.`".$field['dbfield']."`='".$input[$field['dbfield']]."'";
					} elseif ($field['html_type']==HTML_CHECKBOX_LARGE) {
						$where.=" AND a.`".$field['dbfield']."` LIKE '|%".$input[$field['dbfield']]."%|'";
					}
				}
				break;

			case HTML_CHECKBOX_LARGE:
				if (isset($input[$field['dbfield']])) {
					if ($field['html_type']==HTML_SELECT) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="a.`".$field['dbfield']."`='".$input[$field['dbfield']][$j]."' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					} elseif ($field['html_type']==HTML_CHECKBOX_LARGE) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="a.`".$field['dbfield']."` LIKE '|%".$input[$field['dbfield']][$j]."%|' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					}
				}
				break;

			case HTML_DATE:
				if (isset($input[$field['dbfield'].'_max'])) {
					$where.=" AND a.`".$field['dbfield']."`>=DATE_SUB(now(),INTERVAL ".$input[$field['dbfield'].'_max']." YEAR)";
				}
				if (isset($input[$field['dbfield'].'_min'])) {
					$where.=" AND a.`".$field['dbfield']."`<=DATE_SUB(now(),INTERVAL ".$input[$field['dbfield'].'_min']." YEAR)";
				}
				break;

			case HTML_LOCATION:
				if (isset($input[$field['dbfield'].'_country'])) {
					$where.=" AND a.`".$field['dbfield']."_country`='".$input[$field['dbfield'].'_country']."'";
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='".$input[$field['dbfield'].'_country']."'";
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						list($prefered_input,$num_states)=mysql_fetch_row($res);
						if ($prefered_input=='s' && !empty($num_states)) {
							if (isset($input[$field['dbfield'].'_state'])) {
								$where.=" AND a.`".$field['dbfield']."_state`='".$input[$field['dbfield'].'_state']."'";
								$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`='".$input[$field['dbfield'].'_state']."'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									if (isset($input[$field['dbfield'].'_city'])) {
										$where.=" AND a.`".$field['dbfield']."_city`='".$input[$field['dbfield'].'_city']."'";
									}
								}
							}
						} elseif ($prefered_input=='z') {
							if (isset($input[$field['dbfield'].'_zip']) && isset($input[$field['dbfield'].'_dist'])) {
								$query="SELECT RADIANS(`latitude`),RADIANS(`longitude`) FROM `{$dbtable_prefix}loc_zips` WHERE `zipcode`='".$input[$field['dbfield'].'_zip']."'";
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									list($latitude,$longitude)=mysql_fetch_row($res);
									// earth radius=3956 miles =6367 km; 3956*2=7912
									// Haversine Formula: (more exact for small distances)
									$where.=" AND (a.`latitude`+a.`longitude`)<>0 AND (7912*asin(sqrt(pow(sin((".(float)$latitude."-RADIANS(a.`latitude`))/2),2)+cos(".(float)$latitude.")*cos(RADIANS(a.`latitude`))*pow(sin((".(float)$longitude."-RADIANS(a.`longitude`))/2),2))))<=".(int)$input[$field['dbfield'].'_dist'];
									// Law of Cosines for Spherical Trigonometry; 60*1.1515=69.09; 1.1515=miles in a degree
//									$where.=" AND (69.09*DEGREES(ACOS(SIN(".(float)$latitude.")*SIN(RADIANS(`latitude`))+COS(".(float)$latitude.")*COS(RADIANS(`latitude`))*COS(".(float)$longitude."-RADIANS(`longitude`)))))<=".(int)$input[$field['dbfield'].'_dist'];
								} else {
// should not return any result or at least warn the user that the zip code he entered was not found.
								}
							}
						}
					}
				}	// if (!empty($input[$field['dbfield'].'_country']))
				break;

		}	//switch ($field['search_type'])
	} // the for() that constructs the where

	$query="SELECT a.`fk_user_id` FROM $from WHERE $where";
//print $query;
//die;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$search_md5=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='$search_md5',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($user_ids);

// get the details for the found user_ids...unfortunately that's another query
$profile=array();
if (!empty($totalrows)) {
	// handle prev/next from profile.php
	if (isset($_GET['uid']) && isset($_GET['go']) && ($_GET['go']==1 || $_GET['go']==-1)) {
		$uid=(int)$_GET['uid'];
		$key=array_search($uid,$user_ids)+$_GET['go'];
		if (isset($user_ids[$key])) {
			$uid=(int)$user_ids[$key];
			redirect2page('admin/profile.php',array(),'uid='.$uid.'&search='.$search_md5);
		}
	}
	$user_ids=array_slice($user_ids,$o,$r);
	$query="SELECT `fk_user_id`,`_user`,`_photo`,`status`,`del`";
	for ($i=1;$i<=RELEVANT_FIELDS;++$i) {
		switch ($_pfields[$i]['html_type']) {

			case HTML_LOCATION:
				$query.=','.$_pfields[$i]['dbfield'].'_country,'.$_pfields[$i]['dbfield'].'_state,'.$_pfields[$i]['dbfield'].'_city,'.$_pfields[$i]['dbfield'].'_zip';
				break;

			default:
				$query.=','.$_pfields[$i]['dbfield'];

		}
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id` IN ('".join("','",$user_ids)."') ORDER BY `_user`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		for ($i=1;$i<=RELEVANT_FIELDS;++$i) {
			$rsrow[$_pfields[$i]['dbfield'].'_label']=$_pfields[$i]['label'];
			switch ($_pfields[$i]['html_type']) {

				case HTML_TEXTFIELD:
					$rsrow[$_pfields[$i]['dbfield']]=sanitize_and_format($rsrow[$_pfields[$i]['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
					break;

				case HTML_TEXTAREA:
					$rsrow[$_pfields[$i]['dbfield']]=sanitize_and_format($rsrow[$_pfields[$i]['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
					break;

				case HTML_SELECT:
					$rsrow[$_pfields[$i]['dbfield']]=sanitize_and_format($_pfields[$i]['accepted_values'][$rsrow[$_pfields[$i]['dbfield']]],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
					break;

				case HTML_CHECKBOX_LARGE:
					$rsrow[$_pfields[$i]['dbfield']]=sanitize_and_format(vector2string_str($_pfields[$i]['accepted_values'],$rsrow[$_pfields[$i]['dbfield']]),TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
					break;

				case HTML_DATE:
					$rsrow[$_pfields[$i]['dbfield']]=sanitize_and_format($rsrow[$_pfields[$i]['dbfield']],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
					break;

				case HTML_LOCATION:
					$rsrow[$_pfields[$i]['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$rsrow[$_pfields[$i]['dbfield'].'_country'],'-');
					if (!empty($rsrow[$_pfields[$i]['dbfield'].'_state'])) {
						$rsrow[$_pfields[$i]['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$rsrow[$_pfields[$i]['dbfield'].'_state'],'-');
					}
					if (!empty($rsrow[$_pfields[$i]['dbfield'].'_city'])) {
						$rsrow[$_pfields[$i]['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$rsrow[$_pfields[$i]['dbfield'].'_city'],'-');
					}
					break;
			}
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
		$profile[]=$rsrow;
	}

	$_GET=array('search'=>$search_md5);
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
	$tpl->set_var('totalrows',$totalrows);
}

$tpl->set_file('content','member_results.html');
$tpl->set_loop('profile',$profile);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('search_md5',$search_md5);
$return='member_results.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$return.='?'.$_SERVER['QUERY_STRING'];
} else {
	$return.='?search='.$search_md5;
}
$tpl->set_var('return',rawurlencode($return));
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP | TPL_OPTIONAL);
$tpl->drop_loop('profile');

$tplvars['title']='Search Results';
$tplvars['css']='member_results.css';
include 'frame.php';
?>
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
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$output=array();

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$input=array();
$user_ids=array();
$do_query=true;
if (!empty($output['search_md5'])) {
	// if we have a query cache, retrieve all from cache
	$query="SELECT `results`,`search` FROM `{$dbtable_prefix}site_searches` WHERE `search_md5`='".$output['search_md5']."' AND `search_type`=".SEARCH_USER;
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
	$input['user']=sanitize_and_format_gpc($_GET,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
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

	// see what fields we received from the search and sanitize them
	for ($i=0;isset($basic_search_fields[$i]);++$i) {
		$field=$_pfields[$basic_search_fields[$i]];
		switch ($field['search_type']) {

			case FIELD_SELECT:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (empty($input[$field['dbfield']])) {
					unset($input[$field['dbfield']]);
				}
				break;

			case FIELD_CHECKBOX_LARGE:
				$input[$field['dbfield']]=sanitize_and_format_gpc($_GET,$field['dbfield'],TYPE_INT,0,0);
				if (empty($input[$field['dbfield']])) {
					unset($input[$field['dbfield']]);
				}
				break;

			case FIELD_RANGE:
				$input[$field['dbfield'].'_min']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_min',TYPE_INT,0,0);
				$input[$field['dbfield'].'_max']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_max',TYPE_INT,0,0);
				if (empty($input[$field['dbfield'].'_max'])) {
					unset($input[$field['dbfield'].'_max']);
				}
				if (empty($input[$field['dbfield'].'_min'])) {
					unset($input[$field['dbfield'].'_min']);
				}
				break;

			case FIELD_LOCATION:
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
					$input[$field['dbfield'].'_zip']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_zip',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
					$input[$field['dbfield'].'_dist']=sanitize_and_format_gpc($_GET,$field['dbfield'].'_dist',TYPE_INT,0,0);
					if (empty($input[$field['dbfield'].'_zip']) || empty($input[$field['dbfield'].'_dist'])) {
						unset($input[$field['dbfield'].'_zip'],$input[$field['dbfield'].'_dist']);
					}
				} else {
					unset($input[$field['dbfield'].'_country']);
				}
				break;

		}	//switch ($field['search_type'])
	} // for() each $basic_search_fields
}

if ($do_query) {
	$where='1';
	$from="`{$dbtable_prefix}user_profiles` a";

	if (isset($input['user'])) {
		$where.=" AND a.`_user` LIKE '".$input['user']."%'";
	}
	if (isset($input['pstat'])) {	// profile status
		$where.=" AND a.`status`=".$input['pstat'];
	}
	if (isset($input['astat'])) {	// account status
		$where.=" AND a.`fk_user_id`=b.`".USER_ACCOUNT_ID."` AND b.`status`=".$input['astat'];
		$from.=",".USER_ACCOUNTS_TABLE." b";
	}
	if (isset($input['membership'])) {
		$where.=" AND b.`membership`=".$input['membership'];
		if (!isset($input['astat'])) {
			$where.=" AND a.`fk_user_id`=b.`".USER_ACCOUNT_ID."`";
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

	// continue building the where clause of the query based on the input values we have.
	for ($i=0;isset($basic_search_fields[$i]);++$i) {
		$field=$_pfields[$basic_search_fields[$i]];
		switch ($field['search_type']) {

			case FIELD_SELECT:
				if (isset($input[$field['dbfield']])) {
					if ($field['field_type']==FIELD_SELECT) {
						$where.=" AND a.`".$field['dbfield']."`='".$input[$field['dbfield']]."'";
					} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
						$where.=" AND a.`".$field['dbfield']."` LIKE '%|".$input[$field['dbfield']]."|%'";
					}
				}
				break;

			case FIELD_CHECKBOX_LARGE:
				if (isset($input[$field['dbfield']])) {
					if ($field['field_type']==FIELD_SELECT) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="a.`".$field['dbfield']."`='".$input[$field['dbfield']][$j]."' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
						if (count($input[$field['dbfield']])) {
							$where.=" AND (";
							for ($j=0;isset($input[$field['dbfield']][$j]);++$j) {
								$where.="a.`".$field['dbfield']."` LIKE '%|".$input[$field['dbfield']][$j]."|%' OR ";
							}
							$where=substr($where,0,-4);	// substract the last ' OR '
							$where.=')';
						}
					}
				}
				break;

			case FIELD_RANGE:
				$now=gmdate('YmdHis');
				if (isset($input[$field['dbfield'].'_max'])) {
					if ($field['field_type']==FIELD_DATE) {
						$where.=" AND a.`".$field['dbfield']."`>=DATE_SUB('$now',INTERVAL ".$input[$field['dbfield'].'_max']." YEAR)";
					} elseif ($field['field_type']==FIELD_SELECT) {
						$where.=" AND `".$field['dbfield']."`<=".$input[$field['dbfield'].'_max'];
					}
				}
				if (isset($input[$field['dbfield'].'_min'])) {
					if ($field['field_type']==FIELD_DATE) {
						$where.=" AND a.`".$field['dbfield']."`<=DATE_SUB('$now',INTERVAL ".$input[$field['dbfield'].'_min']." YEAR)";
					} elseif ($field['field_type']==FIELD_SELECT) {
						$where.=" AND `".$field['dbfield']."`>=".$input[$field['dbfield'].'_min'];
					}
				}
				break;

			case FIELD_LOCATION:
				if (isset($input[$field['dbfield'].'_country'])) {
					$where.=" AND a.`".$field['dbfield']."_country`=".$input[$field['dbfield'].'_country'];
					$query="SELECT `prefered_input`,`num_states` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`=".$input[$field['dbfield'].'_country'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					if (mysql_num_rows($res)) {
						list($prefered_input,$num_states)=mysql_fetch_row($res);
						if ($prefered_input=='s' && !empty($num_states)) {
							if (isset($input[$field['dbfield'].'_state'])) {
								$where.=" AND a.`".$field['dbfield']."_state`=".$input[$field['dbfield'].'_state'];
								$query="SELECT `num_cities` FROM `{$dbtable_prefix}loc_states` WHERE `state_id`=".$input[$field['dbfield'].'_state'];
								if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
								if (mysql_num_rows($res)) {
									if (isset($input[$field['dbfield'].'_city'])) {
										$where.=" AND a.`".$field['dbfield']."_city`=".$input[$field['dbfield'].'_city'];
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

	$query="SELECT a.`fk_user_id` FROM $from WHERE $where ORDER BY `fk_user_id` DESC";
//print $query;
//die;
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$user_ids=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$user_ids[]=mysql_result($res,$i,0);
	}
	$serialized_input=serialize($input);
	$output['search_md5']=md5($serialized_input);
	$query="INSERT IGNORE INTO `{$dbtable_prefix}site_searches` SET `search_md5`='".$output['search_md5']."',`search_type`=".SEARCH_USER.",`search`='$serialized_input',`results`='".join(',',$user_ids)."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}
$totalrows=count($user_ids);

// get the details for the found user_ids...unfortunately that's another query
$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
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
	$user_ids=array_slice($user_ids,$o,$r);
	$query="SELECT `fk_user_id`,`_user`,`_photo`,`status`,`del`,`score`";
	foreach ($_pfields as $k=>$field) {
		switch ($field['field_type']) {

			case FIELD_LOCATION:
				$query.=',`'.$field['dbfield'].'_country`,`'.$field['dbfield'].'_state`,`'.$field['dbfield'].'_city`,`'.$field['dbfield'].'_zip`';
				break;

			default:
				$query.=',`'.$field['dbfield'].'`';

		}
	}
	$query.=" FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id` IN ('".join("','",$user_ids)."') ORDER BY `fk_user_id` DESC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		foreach ($_pfields as $k=>$field) {
			$rsrow[$field['dbfield'].'_label']=$field['label'];
			switch ($field['field_type']) {

				case FIELD_TEXTFIELD:
					$rsrow[$field['dbfield']]=sanitize_and_format($rsrow[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					break;

				case FIELD_TEXTAREA:
					$rsrow[$field['dbfield']]=sanitize_and_format($rsrow[$field['dbfield']],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					break;

				case FIELD_SELECT:
				// if we sanitize here " will be rendered as &quot; which is not what we want
				//	$rsrow[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$rsrow[$field['dbfield']]],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					$rsrow[$field['dbfield']]=$field['accepted_values'][$rsrow[$field['dbfield']]];
					break;

				case FIELD_CHECKBOX_LARGE:
					$rsrow[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$rsrow[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					break;

				case FIELD_DATE:
					if ($rsrow[$field['dbfield']]=='0000-00-00') {
						$rsrow[$field['dbfield']]='?';
					}
					break;

				case FIELD_LOCATION:
					$rsrow[$field['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$rsrow[$field['dbfield'].'_country'],'-');
					if (!empty($rsrow[$field['dbfield'].'_state'])) {
						$rsrow[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$rsrow[$field['dbfield'].'_state'],'-');
					}
					if (!empty($rsrow[$field['dbfield'].'_city'])) {
						$rsrow[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$rsrow[$field['dbfield'].'_city'],'-');
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
		$query="SELECT `site_id`,`baseurl`,`active` FROM `user_sites` WHERE `fk_user_id`=".$rsrow['fk_user_id'];
		if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$user_sites=array();
		while ($temp=mysql_fetch_assoc($res2)) {
			$alert='';
			if (!$temp['active']) {
				$alert='class="alert_ppending"';
			}
			$site='<span '.$alert.'>'.$temp['site_id'].' : '.$temp['baseurl'].'</span> <a class="thickbox" href="user_sites_addedit.php?site_id='.$temp['site_id'].'&amp;keepThis=true&amp;TB_iframe=true&amp;width=900">edit</a>';
			if (!empty($temp['baseurl'])) {
				$site.=' <a target="_blank" class="external" href="'.$temp['baseurl'].'">visit</a>';
			}
			$user_sites[]=$site;
		}
		$rsrow['user_sites']=join('</li><li>',$user_sites);
		$loop[]=$rsrow;
	}

	$_GET=array('search'=>$output['search_md5']);
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
	$output['return2me'].='?search='.$output['search_md5'];
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

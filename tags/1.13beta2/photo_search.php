<?php
/******************************************************************************
Etano
===============================================================================
File:                       photo_search.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/photos.inc.php';

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');
define('COLUMNS',3);
$accepted_results_per_page=array(6=>6,12=>12,24=>24,48=>48);

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
// no reason to use the cached results because we would have to re-query the db anyway for the rest of the info
// It will only make sense if we start caching photo data as we do with the profile and blog data.
//$output['search_md5']=sanitize_and_format_gpc($_GET,'search',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

$tplvars['page_title']='';
$input['acclevel_code']='search_photo'; // default access level
$from="`{$dbtable_prefix}user_photos` a";
$where="a.`status`=".STAT_APPROVED." AND a.`del`=0";
$orderby="a.`date_posted` DESC";

$error=false;
// define here all search types
if (isset($_GET['st'])) {
	$input['st']=$_GET['st'];
	switch ($_GET['st']) {

		case 'new':
			//$orderby="a.`date_posted` DESC";	// default
			$tplvars['page_title']=$GLOBALS['_lang'][144];
			break;

		case 'views':
			$input['acclevel_code']='search_photo';
			$orderby="a.`stat_views` DESC";
			$tplvars['page_title']=$GLOBALS['_lang'][145];
			break;

		case 'vote':
			$input['acclevel_code']='search_photo';
			$orderby="a.`stat_votes_total` DESC";
			$tplvars['page_title']=$GLOBALS['_lang'][146];
			break;

		case 'comm':
			$input['acclevel_code']='search_photo';
			$orderby="a.`stat_comments` DESC";
			$tplvars['page_title']=$GLOBALS['_lang'][147];
			break;

		case 'user':
			$input['acclevel_code']='search_photo';
			$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $input['uid']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
				redirect2page('my_photos.php');
			}
			if (!empty($input['uid'])) {
				$where.=" AND a.`fk_user_id`=".$input['uid'];
				require_once _BASEPATH_.'/includes/network_functions.inc.php';
				// if I am a friend with this gorgeous girl show me the hidden stuff also :)
				if (empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) || !is_network_member($input['uid'],$_SESSION[_LICENSE_KEY_]['user']['user_id'],NET_FRIENDS)) {
					$where.=" AND a.`is_private`=0";
				}
			} else {
				$error=true;
			}
			$tplvars['page_title']=sprintf($GLOBALS['_lang'][143],get_user_by_userid($input['uid']));
			break;

		case 'priv':
			$input['acclevel_code']='auth';
			$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
			if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && $input['uid']==$_SESSION[_LICENSE_KEY_]['user']['user_id']) {
				redirect2page('my_photos.php');
			}
			$user_name=get_user_by_userid($input['uid']);
			if (!empty($input['uid'])) {
				require_once _BASEPATH_.'/includes/network_functions.inc.php';
				// if I am a friend with this gorgeous girl show me the hidden stuff :)
				if (!empty($_SESSION[_LICENSE_KEY_]['user']['user_id']) && is_network_member($input['uid'],$_SESSION[_LICENSE_KEY_]['user']['user_id'],NET_FRIENDS)) {
					$where.=" AND a.`fk_user_id`=".$input['uid']." AND `is_private`=1";
				} else {
					$output['no_results']=sprintf($GLOBALS['_lang'][277],_BASEURL_.'/profile.php?uid='.$input['uid'],$user_name);
					$error=true;
				}
			} else {
				$error=true;
			}
			$tplvars['page_title']=sprintf($GLOBALS['_lang'][279],$user_name);
			break;

		case 'field':
			$input['acclevel_code']='search_photo';
			$input['f']=sanitize_and_format_gpc($_GET,'f',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$input['v']=sanitize_and_format_gpc($_GET,'v',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			if (!empty($input['f']) && !empty($input['v'])) {
				$field_ok=false;
				$fid=0;
				foreach ($_pfields as $k=>&$field) {
					if ($field->config['dbfield']==$input['f'] && get_class($field)=='field_select') {
						$field_ok=true;
						$fid=$k;
						break;
					}
				}
				if ($field_ok) {
					$from.=",`{$dbtable_prefix}user_profiles` b";
					$where.=" AND a.`is_private`=0 AND a.`fk_user_id`=b.`fk_user_id` AND b.`".$input['f']."`='".$input['v']."'";
					$field_value=isset($_pfields[$fid]->config['accepted_values'][$input['v']]) ? $_pfields[$fid]->config['accepted_values'][$input['v']] : '';
					$tplvars['page_title']=sprintf($GLOBALS['_lang'][143],$field_value);
				} else {
					$error=true;
				}
			}
			break;

		case 'tag':
			$input['acclevel_code']='search_photo';
			$input['tags']=sanitize_and_format_gpc($_GET,'tags',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$tags=$input['tags'];
			// remove extra spaces and words with less than 3 chars
			$input['tags']=trim(preg_replace(array("/['\"%<>\+-]/","/\s\s+/","/\b[^\s]{1,3}\b/"),array(' ',' ',''),$input['tags']));
			if (!empty($input['tags'])) {
				$where.=" AND a.`is_private`=0 AND MATCH (a.`caption`) AGAINST ('".$input['tags']."')";
			} else {
				$error=true;
			}
			$tplvars['page_title']=sprintf($GLOBALS['_lang'][148],$tags);
			break;

	}
}
check_login_member($input['acclevel_code']);

$totalrows=0;
$loop_rows=array();
if (!$error) {
	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);

	if (!empty($totalrows)) {
		if ($o>=$totalrows) {
			$o=$totalrows-$r;
			$o=$o>=0 ? $o : 0;
		}
		$query="SELECT a.`photo_id`,a.`fk_user_id`,a.`_user` as `user`,a.`photo`,a.`allow_comments`,a.`caption`,a.`stat_views`,a.`stat_comments`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where ORDER BY $orderby LIMIT $o,$r";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$loop_items=array();
		$i=1;
		$rows=0;
		while ($rsrow=mysql_fetch_assoc($res)) {
			$photo_ids[]=$rsrow['photo_id'];
			$rsrow['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['date_format'],$rsrow['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
			$rsrow['is_private']=empty($rsrow['is_private']) ? $GLOBALS['_lang'][139] : $GLOBALS['_lang'][138];
			$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$rsrow['class']='';
			if ($i%COLUMNS==1) {
				$rsrow['class'].='first';
			}
			if (isset($_list_of_online_members[$rsrow['fk_user_id']])) {
				$rsrow['class'].=' is_online';
				$rsrow['user_online_status']=$GLOBALS['_lang'][102];
			} else {
				$rsrow['user_online_status']=$GLOBALS['_lang'][103];
			}
			$loop_items[]=$rsrow;
			if ($i%COLUMNS==0) {
				$loop_items[count($loop_items)-1]['class'].=' last';
				$loop_rows[$rows]['loop_items']=$loop_items;
				$loop_items=array();
				++$rows;
			}
			++$i;
		}
	// one more time for $i%COLUMNS
		if (!empty($loop_items)) {
			$loop_items[count($loop_items)-1]['class'].=' last';
			$loop_rows[$rows]['loop_items']=$loop_items;
		}
		$loop_rows[0]['class']='first';
		if (empty($rows)) {
			$loop_rows[0]['class'].=' last';
		} else {
			$loop_rows[count($loop_rows)-1]['class']='last';
		}

		$output['pager2']=pager($totalrows,$o,$r);
	}
}

if (empty($loop_rows) && empty($output['no_results'])) {
	$output['no_results']=$GLOBALS['_lang'][278];
}
$output['return2me']='photo_search.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','photo_search.html');
$tpl->set_loop('loop_rows',$loop_rows);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop_rows');
$tpl->drop_var('output.pager2');
unset($loop_rows);

$tplvars['title']=$tplvars['page_title'];
$tplvars['page']='photo_search';
$tplvars['css']='photo_search.css';
if (is_file('photo_search_left.php')) {
	include 'photo_search_left.php';
}
include 'frame.php';

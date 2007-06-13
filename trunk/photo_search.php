<?php
/******************************************************************************
newdsb
===============================================================================
File:                       photo_search.php
$Revision: 21 $
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
check_login_member('search_photo');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
define('COLUMNS',3);
$accepted_results_per_page=array('6'=>6,'12'=>12,'24'=>24,'48'=>48);

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);

$input['acclevel_id']=17; // default access level is the one for advanced search!!!!
$from="`{$dbtable_prefix}user_photos` a";
$where="a.`is_private`=0 AND a.`status`='".STAT_APPROVED."' AND a.`del`=0";
$orderby="a.`date_posted` DESC";

$error=false;
// define here all search types
if (isset($_GET['st'])) {
	$input['st']=$_GET['st'];
	switch ($_GET['st']) {

		case 'new':
			//$orderby="a.`date_posted` DESC";	// default
			$tplvars['page_title']='Browse Newest Photos';
			break;

		case 'views':
			$input['acclevel_id']=17;
			$orderby="a.`stat_views` DESC";
			$tplvars['page_title']='Browse Most Popular Photos';
			break;

		case 'vote':
			$input['acclevel_id']=17;
			$orderby="a.`stat_votes_total` DESC";
			$tplvars['page_title']='Browse Most Voted Photos';
			break;

		case 'comm':
			$input['acclevel_id']=17;
			$orderby="a.`stat_comments` DESC";
			$tplvars['page_title']='Browse Most Discussed Photos';
			break;

		case 'user':
			$input['acclevel_id']=17;
			$input['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
			if (isset($_SESSION['user']['user_id']) && $input['uid']==$_SESSION['user']['user_id']) {
				redirect2page('my_photos.php');
			}
			if (!empty($input['uid'])) {
				$where="a.`fk_user_id`='".$input['uid']."' AND ".$where;
			} else {
				$error=true;
			}
			$tplvars['page_title']=sprintf('%s\'s Photos',get_user_by_userid($input['uid']));	// translate
			break;

		case 'field':
			$input['acclevel_id']=17;
			$input['f']=sanitize_and_format_gpc($_GET,'f',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$input['v']=sanitize_and_format_gpc($_GET,'v',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			if (!empty($input['f']) && !empty($input['v'])) {
				$field_ok=false;
				$fid=0;
				foreach ($_pfields as $k=>$field) {
					if ($field['dbfield']==$input['f'] && $field['field_type']==FIELD_SELECT) {
						$field_ok=true;
						$fid=$k;
						break;
					}
				}
				if ($field_ok) {
					$from.=",`{$dbtable_prefix}user_profiles` b";
					$where="a.`fk_user_id`=b.`fk_user_id` AND ".$where." AND b.`".$input['f']."`='".$input['v']."'";
					$field_value=isset($_pfields[$fid]['accepted_values'][$input['v']]) ? $_pfields[$fid]['accepted_values'][$input['v']] : '';
					$tplvars['page_title']=sprintf('Browse %s Photos',$field_value);	// translate
				} else {
					$error=true;
				}
			}
			break;

		case 'tag':
			$input['acclevel_id']=17;
			$input['tags']=sanitize_and_format_gpc($_GET,'tags',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			$tags=$input['tags'];
			// remove extra spaces and words with less than 3 chars
			$input['tags']=trim(preg_replace(array("/['\"%<>\+-]/","/\s\s+/","/\b[^\s]{1,3}\b/"),array(' ',' ',''),$input['tags']));
			if (!empty($input['tags'])) {
				$where="MATCH (a.`caption`) AGAINST ('".$input['tags']."') AND ".$where;
			} else {
				$error=true;
			}
			$tplvars['page_title']=sprintf('Results for "%s"',$tags);
			break;

		default:
			break;

	}
}

$totalrows=0;
if (!$error) {
	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);
}

$loop_rows=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT a.`photo_id`,a.`fk_user_id`,a.`_user` as `user`,a.`photo`,a.`allow_comments`,a.`caption`,a.`stat_views`,a.`stat_comments`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where ORDER BY $orderby LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$loop_items=array();
	$i=1;
	$rows=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_posted']=strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
		$rsrow['is_private']=sprintf('%1$s',empty($rsrow['is_private']) ? 'public' : 'private');	// translate this
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['class']='';
		if ($i%COLUMNS==1) {
			$rsrow['class'].='first';
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

$output['return']='photo_search.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','photo_search.html');
$tpl->set_loop('loop_rows',$loop_rows);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop_rows');
$tpl->drop_var('output.pager2');
unset($loop_rows);

$tplvars['title']='Photos';
$tplvars['page']='photo_search';
$tplvars['css']='photo_search.css';
if (is_file('photo_search_left.php')) {
	include 'photo_search_left.php';
}
include 'frame.php';
?>
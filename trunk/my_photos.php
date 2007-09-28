<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_photos.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('upload_photos');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
define('COLUMNS',3);
$accepted_results_per_page=array('6'=>6,'12'=>12,'24'=>24,'48'=>48);

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$where="`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' AND `del`=0";
$from="`{$dbtable_prefix}user_photos`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop_rows=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `photo_id`,`photo`,`is_main`,`is_private`,`caption`,`status`,`reject_reason`,`stat_views`,`stat_comments`,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where ORDER BY `date_posted` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$loop_items=array();
	$i=1;
	$rows=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_posted']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['date_format'],$rsrow['date_posted']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
		$rsrow['is_private']=sprintf('%1$s',empty($rsrow['is_private']) ? 'public' : 'private');	// translate this
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['class']='';
		if ($rsrow['status']==STAT_EDIT) {
			$rsrow['status']='stat_edit';
			$rsrow['reject_reason']=rawurlencode($rsrow['reject_reason']);
		} else {
			unset($rsrow['reject_reason']);
			if ($rsrow['status']==STAT_PENDING) {
				$rsrow['status']='stat_pending';
				$rsrow['stat_pending']=true;
			}
		}
		if ($i%COLUMNS==1) {
			$rsrow['class'].=' first';
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

$output['return2me']='my_photos.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_photos.html');
$tpl->set_loop('loop_rows',$loop_rows);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop_rows');
unset($loop_rows);
$tpl->drop_var('output.pager2');

$tplvars['title']='My Photos';
$tplvars['page_title']='My Photos';
$tplvars['page']='my_photos';
$tplvars['css']='my_photos.css';
if (is_file('my_photos_left.php')) {
	include 'my_photos_left.php';
}
include 'frame.php';

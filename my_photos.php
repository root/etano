<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_photos.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(-1);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
define('COLUMNS',3);
$accepted_results_per_page=array('6'=>6,'12'=>12,'24'=>24,'48'=>48);

$input=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);

$where="`fk_user_id`='".$_SESSION['user']['user_id']."' AND `del`=0";
$from="`{$dbtable_prefix}user_photos`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop_rows=array();
if (!empty($totalrows)) {
	$query="SELECT *,UNIX_TIMESTAMP(`date_posted`) as `date_posted` FROM $from WHERE $where ORDER BY `date_posted` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$loop_items=array();
	$i=1;
	$rows=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['date_posted']=strftime($_user_settings['date_format'],$rsrow['date_posted']+$_user_settings['time_offset']);
		$rsrow['is_private']=sprintf('%1s',empty($rsrow['is_private']) ? 'public' : 'private');	// translate this
		$rsrow['caption']=sanitize_and_format($rsrow['caption'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
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
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$return='my_photos.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$return.='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($return);
$tpl->set_file('content','my_photos.html');
$tpl->set_loop('loop_rows',$loop_rows);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop_rows');
$tpl->drop_var('pager2');

$tplvars['title']='My Photos';
$tplvars['page_title']='My Photos';
$tplvars['page']='my_photos';
$tplvars['css']='my_photos.css';
if (is_file('my_photos_left.php')) {
	include 'my_photos_left.php';
}
include 'frame.php';
?>
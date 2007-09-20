<?php
/******************************************************************************
Etano
===============================================================================
File:                       products.php
$Revision: 290 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('all');

$dbtable_prefix='';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$where="a.`fk_dev_id`=b.`dev_id`";
$from="`{$dbtable_prefix}products` a,`{$dbtable_prefix}developers` b";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT a.`prod_id`,a.`prod_name`,a.`prod_diz`,a.`prod_pic`,a.`version`,UNIX_TIMESTAMP(a.`last_changed`) as `last_changed`,a.`price`,b.`dev_name` FROM $from WHERE $where ORDER BY `last_changed` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['last_changed']=strftime($_SESSION['user']['prefs']['date_format'],$rsrow['last_changed']+$_SESSION['user']['prefs']['time_offset']);
		$rsrow['prod_name']=sanitize_and_format($rsrow['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['prod_diz']=sanitize_and_format($rsrow['prod_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (empty($rsrow['prod_pic']) || !is_file(_PHOTOPATH_.'/products/'.$rsrow['prod_pic'])) {
			unset($rsrow['prod_pic']);
		}
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='products.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.str_replace('&','&amp;',$_SERVER['QUERY_STRING']);
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','products.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);
$tpl->drop_var('output.pager2');

$tplvars['title']='Etano - networking products';
$tplvars['page_title']='Etano Addons';
$tplvars['page']='products';
$tplvars['css']='products.css';
if (is_file('products_left.php')) {
	include 'products_left.php';
}
include 'frame.php';

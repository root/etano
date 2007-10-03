<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/products.php
$Revision: 221 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$dbtable_prefix='';

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}products`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `prod_id`,`prod_name`,`prod_diz`,`prod_pic`,`prod_type`,`version`,`price` FROM $from WHERE $where ORDER BY `prod_id` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['prod_name']=sanitize_and_format($rsrow['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['prod_diz']=sanitize_and_format($rsrow['prod_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['prod_type']=$accepted_module_types[$rsrow['prod_type']];
		if (empty($rsrow['prod_pic'])) {
			unset($rsrow['prod_pic']);
		}
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='products.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','products.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Product Management';
$tplvars['page']='products';
include 'frame.php';

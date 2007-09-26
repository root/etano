<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/admin_accounts.php
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
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where='1';
$from="`{$dbtable_prefix}admin_accounts`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$admin_accounts=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT * FROM $from WHERE $where ORDER BY `admin_id` ASC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['name']=sanitize_and_format($rsrow['name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['status']=$accepted_astats[$rsrow['status']];
		$rsrow['dept_id']=$accepted_admin_depts[$rsrow['dept_id']];
		$rsrow['myclass']=($i%2) ? 'odd_item' : 'even_item';
		$admin_accounts[]=$rsrow;
		++$i;
	}
	$tpl->set_var('pager1',pager($totalrows,$o,$r));
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$tpl->set_file('content','admin_accounts.html');
$tpl->set_loop('admin_accounts',$admin_accounts);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('admin_accounts');
unset($admin_accounts);

$tplvars['title']='Admin Accounts';
$tplvars['page']='admin_accounts';
include 'frame.php';

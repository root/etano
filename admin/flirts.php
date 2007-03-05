<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/flirts.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : _RESULTS_;
$where='1';
$from="`{$dbtable_prefix}flirts`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$flirts=array();
if (!empty($totalrows)) {
	$query="SELECT * FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['flirt_text']=bbcode2html(sanitize_and_format($rsrow['flirt_text'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]));
		$rsrow['myclass']=($i%2) ? 'odd_item' : 'even_item';
		$flirts[]=$rsrow;
		++$i;
	}
	$tpl->set_var('pager1',pager($totalrows,$o,$r));
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}

$tpl->set_file('content','flirts.html');
$tpl->set_loop('flirts',$flirts);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('flirts');

$tplvars['title']='Flirt Management';
include 'frame.php';
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/updates.php
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
$from="`{$dbtable_prefix}updates`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `update_id`,`update_name`,`update_diz` FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['update_name']=sanitize_and_format($rsrow['update_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['update_diz']=sanitize_and_format($rsrow['update_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$query="SELECT `module_code`,`version`,`min-version`,`max-version` FROM `update_requirements` WHERE `fk_update_id`=".$rsrow['update_id'];
		if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$rsrow['requires']=array();
		while ($rsrow2=mysql_fetch_assoc($res2)) {
			$temp=$rsrow2['module_code'];
			if ((float)$rsrow2['version']!=0) {
				$temp.='='.$rsrow2['version'];
			}
			if ((float)$rsrow2['min-version']!=0) {
				$temp.='>='.$rsrow2['min-version'];
			}
			if ((float)$rsrow2['max-version']!=0) {
				$temp.='<='.$rsrow2['max-version'];
			}
			$rsrow['requires'][]=$temp;
		}
		$rsrow['requires']=join(', ',$rsrow['requires']);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='updates.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','updates.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Product Management';
$tplvars['page']='updates';
include 'frame.php';

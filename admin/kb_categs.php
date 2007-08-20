<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/kb_categs.php
$Revision: 207 $
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

$loop=array();
$query="SELECT `kbc_id`,`kbc_title`,`num_articles` FROM `{$dbtable_prefix}kb_categs` WHERE `fk_kbc_id_parent`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['kbc_title']=sanitize_and_format($rsrow['kbc_title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$loop[]=$rsrow;
}

$tpl->set_file('content','kb_categs.html');
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');

$tplvars['title']='Knowledge Base Categories Management';
$tplvars['page']='kb_categs';
$tplvars['css']='kb_categs.css';
include 'frame.php';

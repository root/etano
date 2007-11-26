<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/lang_strings.php
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

$content='';
if (isset($_GET['s'])) {
	$skin=sanitize_and_format($_GET['s'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
} else {
	$skin=get_default_skin_code();
}
$output['skin']=$skin;

$query="SELECT * FROM `{$dbtable_prefix}lang_keys` ORDER BY `lk_id` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$i=0;
$loop=array();
$temp=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['lk']=empty($rsrow['alt_id_text']) ? $rsrow['lk_id'] : sanitize_and_format($rsrow['alt_id_text'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['save_file']=empty($rsrow['save_file']) ? 'global.inc.php' : $rsrow['save_file'];
	$loop[$i]=$rsrow;
	$loop[$i]['lang_value']='';
	if ($rsrow['lk_type']==FIELD_TEXTFIELD) {
		$loop[$i]['tf']=true;
	}
	$temp[$rsrow['lk_id']]=$i;
	++$i;
}
$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='$skin'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$loop[$temp[$rsrow['fk_lk_id']]]['lang_value']=str_replace(array('{','}'),array('&#x007B;','&#x007D;'),sanitize_and_format($rsrow['lang_value'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]));
}

$output['return2me']='lang_strings.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','lang_strings.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Language Strings';
$tplvars['page']='lang_strings';
include 'frame.php';

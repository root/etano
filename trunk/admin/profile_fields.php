<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_fields.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/admin_functions.inc.php';
require_once '../includes/tables/profile_fields.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','profile_fields.html');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
//$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : -1;
//$od=isset($_GET['od']) ? (int)$_GET['od'] : 0;

$default_skin_code=get_default_skin_code();
$where='1';
$from="`{$dbtable_prefix}profile_fields` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_label`=b.`fk_lk_id` AND b.`skin`='$default_skin_code') LEFT JOIN `{$dbtable_prefix}profile_categories` c ON a.`fk_pcat_id`=c.`pcat_id`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	$query="SELECT a.*,b.`lang_value` as `label`,c.`fk_lk_id_pcat` FROM $from WHERE $where ORDER BY a.`order_num` LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$loop[$i]=$rsrow;
		$loop[$i]=sanitize_and_format($loop[$i],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if ($loop[$i]['field_type']==FIELD_SELECT || $loop[$i]['field_type']==FIELD_CHECKBOX_LARGE) {
			$loop[$i]['accepted_values']=lkids2lks(explode('|',substr($loop[$i]['accepted_values'],1,-1)),$default_skin_code);
		} else {
			$loop[$i]['accepted_values']=str_replace('|',', ',substr($loop[$i]['accepted_values'],1,-1));
		}
		$loop[$i]['field_type']=$accepted_fieldtype[$loop[$i]['field_type']];
		$loop[$i]['searchable']=!empty($loop[$i]['searchable']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$loop[$i]['at_registration']=!empty($loop[$i]['at_registration']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$loop[$i]['required']=!empty($loop[$i]['required']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$loop[$i]['fk_pcat_id']=db_key2value("`{$dbtable_prefix}lang_strings`",'`fk_lk_id`','`lang_value`',$loop[$i]['fk_lk_id_pcat']);
		$loop[$i]['myclass']=($i%2) ? 'odd_item' : 'even_item';
		++$i;
	}
	$tpl->set_var('pager1',pager($totalrows,$o,$r));
	$tpl->set_var('pager2',pager($totalrows,$o,$r));
}
$tpl->set_loop('loop',$loop);
$tpl->set_var('field_type',vector2options($accepted_fieldtype,'',array(FIELD_RANGE)));
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('profile_fields');

$tplvars['title']='Profile Fields Management';
$tplvars['css']='profile_fields.css';
$tplvars['page']='profile_fields';
include 'frame.php';


function lkids2lks($lk_ids,$skin) {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN ('".join("','",$lk_ids)."') AND `skin`='$skin'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$myreturn.=mysql_result($res,$i,0).', ';
	}
	if (!empty($myreturn)) {
		$myreturn=substr($myreturn,0,-2);
	}
	return $myreturn;
}
?>
<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_fields.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once 'includes/admin_functions.inc.php';
require_once '../includes/tables/profile_fields.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','profile_fields.html');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : _RESULTS_;
//$ob=isset($_GET['ob']) ? (int)$_GET['ob'] : -1;
//$od=isset($_GET['od']) ? (int)$_GET['od'] : 0;

$where='1';
$from="`{$dbtable_prefix}profile_fields` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`fk_lk_id_label`=b.`fk_lk_id` AND b.`skin`='"._DEFAULT_SKIN_."') LEFT JOIN `{$dbtable_prefix}profile_categories` c ON a.`fk_pcat_id`=c.`pcat_id`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$profile_fields=array();
if (!empty($totalrows)) {
	$query="SELECT a.*,b.`lang_value` as `label`,c.`fk_lk_id_pcat` FROM $from WHERE $where ORDER BY a.`order_num` LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$profile_fields[$i]=$rsrow;
		$profile_fields[$i]=sanitize_and_format($profile_fields[$i],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
		$profile_fields[$i]['html_type']=$accepted_htmltype[$profile_fields[$i]['html_type']];
		$profile_fields[$i]['searchable']=!empty($profile_fields[$i]['searchable']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$profile_fields[$i]['at_registration']=!empty($profile_fields[$i]['at_registration']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$profile_fields[$i]['required']=!empty($profile_fields[$i]['required']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$profile_fields[$i]['fk_pcat_id']=db_key2value("`{$dbtable_prefix}lang_strings`",'`fk_lk_id`','`lang_value`',$profile_fields[$i]['fk_lk_id_pcat']);
		$profile_fields[$i]['accepted_values']=str_replace('|',', ',substr($profile_fields[$i]['accepted_values'],1,-1));
		$profile_fields[$i]['myclass']=($i%2) ? 'odd_item' : 'even_item';
		++$i;
	}
	$tpl->set_var('pager1',create_pager2($totalrows,$o,$r));
	$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
}
$tpl->set_loop('profile_fields',$profile_fields);
$tpl->set_var('html_type',vector2options($accepted_htmltype));
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('profile_fields');

$tplvars['title']='Profile Fields Management';
include 'frame.php';
?>
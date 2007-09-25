<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/profile_fields.php
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
require_once '../includes/tables/profile_fields.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

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
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT a.`pfield_id`,a.`dbfield`,a.`field_type`,a.`searchable`,a.`at_registration`,a.`reg_page`,a.`required`,a.`accepted_values`,b.`lang_value` as `label`,c.`fk_lk_id_pcat` FROM $from WHERE $where ORDER BY a.`order_num` LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$i=0;
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$loop[$i]=$rsrow;
/*
		if ($loop[$i]['field_type']==FIELD_SELECT || $loop[$i]['field_type']==FIELD_CHECKBOX_LARGE) {
			$loop[$i]['accepted_values']=lkids2lks(explode('|',substr($loop[$i]['accepted_values'],1,-1)),$default_skin_code);
		} else {
			$loop[$i]['accepted_values']=str_replace('|',', ',substr($loop[$i]['accepted_values'],1,-1));
		}
		if ($loop[$i]['field_type']==FIELD_LOCATION) {
			$loop[$i]['dbfield'].='_country';	// just for display
		}
*/
		$loop[$i]['field_type']=$accepted_fieldtype[$loop[$i]['field_type']];
		$loop[$i]['searchable']=!empty($loop[$i]['searchable']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$loop[$i]['reg_page']=(!empty($loop[$i]['reg_page']) && $loop[$i]['at_registration']==1) ? $loop[$i]['reg_page'] : ' - ';
		$loop[$i]['required']=!empty($loop[$i]['required']) ? '<img src="skin/images/check.gif" alt="" />' : '';
		$loop[$i]['fk_pcat_id']=db_key2value("`{$dbtable_prefix}lang_strings`",'`fk_lk_id`','`lang_value`',$loop[$i]['fk_lk_id_pcat']);
		$loop[$i]['myclass']=($i%2) ? 'odd_item' : 'even_item';
		++$i;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['field_type']=vector2options($accepted_fieldtype,'',array(FIELD_RANGE));
$output['return2me']='profile_fields.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me2']=$output['return2me'];	// this is used in the add form (with method="get")
$output['return2me']=rawurlencode($output['return2me']);

$tpl->set_file('content','profile_fields.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('profile_fields');

$tplvars['title']='Profile Fields Management';
$tplvars['css']='profile_fields.css';
$tplvars['page']='profile_fields';
include 'frame.php';

function lkids2lks($lk_ids,$skin) {
	$temp=array();
	global $dbtable_prefix;
	$query="SELECT `fk_lk_id`,`lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `fk_lk_id` IN ('".join("','",$lk_ids)."') AND `skin`='$skin'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	foreach ($lk_ids as $v) {
		$temp[$v]='?';
	}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$temp[$rsrow['fk_lk_id']]=$rsrow['lang_value'];
	}
	return join(', ',$temp);
}

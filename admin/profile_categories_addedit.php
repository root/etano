<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/profile_categories_addedit.php
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
require_once '../includes/tables/profile_categories.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$profile_categories=$profile_categories_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$profile_categories=$_SESSION['topass']['input'];
} elseif (isset($_GET['pcat_id']) && !empty($_GET['pcat_id'])) {
	$pcat_id=(int)$_GET['pcat_id'];
	$query="SELECT * FROM `{$dbtable_prefix}profile_categories` WHERE `pcat_id`='$pcat_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_categories=mysql_fetch_assoc($res);
	}
	$profile_categories['pcat_name']='';
	$query="SELECT `lang_value` FROM `{$dbtable_prefix}lang_strings` WHERE `skin`='"._DEFAULT_SKIN_."' AND `fk_lk_id`='".$profile_categories['fk_lk_id_pcat']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$profile_categories['pcat_name']=mysql_result($res,0,0);
	}
	$profile_categories=sanitize_and_format($profile_categories,TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
}

$query="SELECT `m_value`,`m_name` FROM `{$dbtable_prefix}memberships`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$memberships=array();
while ($rsrow=mysql_fetch_row($res)) {
	$memberships[$rsrow[0]]=$rsrow[1];
}

$profile_categories['access_level']=vector2checkboxes_new($memberships,array(),'access_level',$profile_categories['access_level'],3);

$tpl->set_file('content','profile_categories_addedit.html');
$tpl->set_var('profile_categories',$profile_categories);
if (isset($_GET['o'])) {
	$tpl->set_var('o',$_GET['o']);
}
if (isset($_GET['r'])) {
	$tpl->set_var('r',$_GET['r']);
}
if (isset($_GET['ob'])) {
	$tpl->set_var('ob',$_GET['ob']);
}
if (isset($_GET['od'])) {
	$tpl->set_var('od',$_GET['od']);
}
$tpl->process('content','content');

$tplvars['title']='Profile Categories Management';
include 'frame.php';


function vector2checkboxes_new($show_vector,$excluded_keys_vector,$checkname,$binvalue,$table_cols=1,$showlabel=true) {
	$myreturn="<table class=\"smart_table\">\n";
	$myreturn.="<tr>\n\t<td colspan=\"{$table_cols}\"><input type=\"checkbox\" name=\"check_all\" id=\"check_all\" onclick=\"check_uncheck(this.form)\" /><label for=\"check_all\">All</label></td>\n</tr>\n";
	$i=0;
	$row=0;
	$myvector=array_flip(array_diff(array_flip($show_vector),$excluded_keys_vector));
	$total_vals=count($myvector);
	$i=1;
	while (list($k,$v)=each($myvector)) {
		if ((($i-1)%$table_cols)==0) {$myreturn.="<tr>\n";}
		$myreturn.="\t<td>";
		$myreturn.="<input type=\"checkbox\" id=\"{$checkname}_$k\" name=\"{$checkname}[$k]\"";
		if (isset($binvalue) && ($binvalue>0) && (((int)$binvalue)&((int)$k))) {
			$myreturn.=' checked="checked"';
		}
		$myreturn.=' value="1" />';
		if ($showlabel) {
			$myreturn.="<label for=\"{$checkname}_$k\">$v</label>";
		}
		$myreturn.="</td>\n";
		if ($i%$table_cols==0) {$myreturn.="</tr>\n";}
		++$i;
	}
	$rest=($i-1)%$table_cols;
	if ($rest!=0) {
		$colspan=$table_cols-$rest;
		$myreturn.="\t<td".(($colspan==1) ? ("") : (" colspan=\"$colspan\""))."></td>\n</tr>\n";
	}
	$myreturn.="</table>\n";
	return $myreturn;
}
?>
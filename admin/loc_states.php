<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_states.php
$Revision: 57 $
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
$co=isset($_GET['co']) ? (int)$_GET['co'] : 0;
$cr=isset($_GET['cr']) ? (int)$_GET['cr'] : _RESULTS_;
$country_id=isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;
$country='';

$states=array();
if (!empty($country_id)) {
	$query="SELECT `country` FROM `{$dbtable_prefix}loc_countries` WHERE `country_id`='$country_id'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$country=mysql_result($res,0,0);
	}
	$where="`fk_country_id`='$country_id'";
	$from="`{$dbtable_prefix}loc_states`";

	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);

	if (!empty($totalrows)) {
		$query="SELECT `state_id`,`state`,`num_cities` FROM $from WHERE $where ORDER BY `state` ASC LIMIT $o,$r";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$i=0;
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['state']=sanitize_and_format($rsrow['state'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
			$rsrow['myclass']=($i%2) ? 'odd_item' : 'even_item';
			$states[]=$rsrow;
			++$i;
		}
		$tpl->set_var('pager1',create_pager2($totalrows,$o,$r));
		$tpl->set_var('pager2',create_pager2($totalrows,$o,$r));
	}
}

$tpl->set_file('content','loc_states.html');
$tpl->set_loop('states',$states);
$tpl->set_var('country_id',$country_id);
$tpl->set_var('country',$country);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('co',$co);
$tpl->set_var('cr',$cr);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('states');

$tplvars['title']='Location Management: States';
include 'frame.php';
?>
<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/loc_cities.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$so=isset($_GET['so']) ? (int)$_GET['so'] : 0;
$sr=isset($_GET['sr']) ? (int)$_GET['sr'] : current($accepted_results_per_page);
$co=isset($_GET['co']) ? (int)$_GET['co'] : 0;
$cr=isset($_GET['cr']) ? (int)$_GET['cr'] : current($accepted_results_per_page);
$state_id=isset($_GET['state_id']) ? (int)$_GET['state_id'] : 0;
$country_id=isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;
$state='';
$country='';

$cities=array();
if (!empty($state_id) && !empty($country_id)) {
	$query="SELECT a.`state`,b.`country` FROM `{$dbtable_prefix}loc_states` a,`{$dbtable_prefix}loc_countries` b WHERE a.`state_id`='$state_id' AND a.`fk_country_id`=b.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($state,$country)=mysql_fetch_row($res);
	}
	$where="`fk_state_id`='$state_id'";
	$from="`{$dbtable_prefix}loc_cities`";

	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);

	if (!empty($totalrows)) {
		if ($o>$totalrows) {
			$o=$totalrows-$r;
		}
		$query="SELECT `city_id`,`city`,`latitude`,`longitude` FROM $from WHERE $where ORDER BY `city` ASC LIMIT $o,$r";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$i=0;
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['city']=sanitize_and_format($rsrow['city'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$rsrow['myclass']=($i%2) ? 'odd_item' : 'even_item';
			$cities[]=$rsrow;
			++$i;
		}
		$tpl->set_var('pager1',pager($totalrows,$o,$r));
		$tpl->set_var('pager2',pager($totalrows,$o,$r));
	}
}

$tpl->set_file('content','loc_cities.html');
$tpl->set_loop('cities',$cities);
$tpl->set_var('state_id',$state_id);
$tpl->set_var('state',$state);
$tpl->set_var('country_id',$country_id);
$tpl->set_var('country',$country);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('so',$so);
$tpl->set_var('sr',$sr);
$tpl->set_var('co',$co);
$tpl->set_var('cr',$cr);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('cities');
unset($cities);

$tplvars['title']='Location Management: Cities';
$tplvars['page']='loc_cities';
include 'frame.php';
?>
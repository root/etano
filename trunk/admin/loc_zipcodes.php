<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/loc_zipcodes.php
$Revision$
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
$cio=isset($_GET['cio']) ? (int)$_GET['cio'] : 0;
$cir=isset($_GET['cir']) ? (int)$_GET['cir'] : _RESULTS_;
$so=isset($_GET['so']) ? (int)$_GET['so'] : 0;
$sr=isset($_GET['sr']) ? (int)$_GET['sr'] : _RESULTS_;
$co=isset($_GET['co']) ? (int)$_GET['co'] : 0;
$cr=isset($_GET['cr']) ? (int)$_GET['cr'] : _RESULTS_;
$city_id=isset($_GET['city_id']) ? (int)$_GET['city_id'] : 0;
$state_id=isset($_GET['state_id']) ? (int)$_GET['state_id'] : 0;
$country_id=isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;
$city='';
$state='';
$country='';

$zipcodes=array();
if (!empty($state_id) && !empty($country_id)) {
	$query="SELECT a.`city`,b.`state`,c.`country` FROM `{$dbtable_prefix}loc_cities` a,`{$dbtable_prefix}loc_states` b,`{$dbtable_prefix}loc_countries` c WHERE a.`city_id`='$city_id' AND a.`fk_state_id`=b.`state_id` AND a.`fk_country_id`=c.`country_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		list($city,$state,$country)=mysql_fetch_row($res);
	}
	$where="`fk_city_id`='$city_id'";
	$from="`{$dbtable_prefix}loc_zips`";

	$query="SELECT count(*) FROM $from WHERE $where";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$totalrows=mysql_result($res,0,0);

	if (!empty($totalrows)) {
		$query="SELECT `zip_id`,`zipcode`,`latitude`,`longitude` FROM $from WHERE $where ORDER BY `zipcode` ASC LIMIT $o,$r";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$i=0;
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['myclass']=($i%2) ? 'odd_item' : 'even_item';
			$zipcodes[]=$rsrow;
			++$i;
		}
		$tpl->set_var('pager1',pager($totalrows,$o,$r));
		$tpl->set_var('pager2',pager($totalrows,$o,$r));
	}
}

$tpl->set_file('content','loc_zipcodes.html');
$tpl->set_loop('zipcodes',$zipcodes);
$tpl->set_var('city_id',$city_id);
$tpl->set_var('city',$city);
$tpl->set_var('state_id',$state_id);
$tpl->set_var('state',$state);
$tpl->set_var('country_id',$country_id);
$tpl->set_var('country',$country);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->set_var('cio',$cio);
$tpl->set_var('cir',$cir);
$tpl->set_var('so',$so);
$tpl->set_var('sr',$sr);
$tpl->set_var('co',$co);
$tpl->set_var('cr',$cr);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('zipcodes');

$tplvars['title']='Location Management: Zipcodes';
include 'frame.php';
?>
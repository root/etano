<?php
/******************************************************************************
newdsb
===============================================================================
File:                       search_more.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('search_advanced');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$search_fields=array();
foreach ($_pcats as $pcat_id=>$pcat) {
	if (((int)$pcat['access_level']) & ((int)$_SESSION['user']['membership'])) {
		for ($i=0;isset($pcat['fields'][$i]);++$i) {
			if (isset($_pfields[$pcat['fields'][$i]]['searchable'])) {
				$search_fields[]=$pcat['fields'][$i];
			}
		}
	}
}

$search_loop=create_search_form($search_fields);

$tpl->set_file('content','search_more.html');
$tpl->set_loop('search',$search_loop);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('search');
unset($search);

$tplvars['title']='Advanced Search';
$tplvars['page_title']='Advanced search';
$tplvars['page']='search_more';
$tplvars['css']='search_more.css';
if (is_file('search_more_left.php')) {
	include 'search_more_left.php';
}
include 'frame.php';
?>
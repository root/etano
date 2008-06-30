<?php
/******************************************************************************
Etano
===============================================================================
File:                       search_more.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

//define('CACHE_LIMITER','private');
require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
check_login_member('search_advanced');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$search_fields=array();
foreach ($_pcats as $pcat_id=>$pcat) {
	if (((int)$pcat['access_level']) & ((int)$_SESSION[_LICENSE_KEY_]['user']['membership'])) {
		for ($i=0;isset($pcat['fields'][$i]);++$i) {
			if (isset($_pfields[$pcat['fields'][$i]]->config['searchable'])) {
				$search_fields[]=$pcat['fields'][$i];
			}
		}
	}
}

sort($search_fields,SORT_NUMERIC);
$search_loop=create_search_form($search_fields);
$tpl->set_file('content','search_more.html');
$tpl->set_loop('search',$search_loop);
$tpl->process('content','content',TPL_LOOP);
$tpl->drop_loop('search');
unset($search_loop);

$tplvars['title']=$GLOBALS['_lang'][247];
$tplvars['page_title']=$GLOBALS['_lang'][247];
$tplvars['page']='search_more';
$tplvars['css']='search_more.css';
if (is_file('search_more_left.php')) {
	include 'search_more_left.php';
}
include 'frame.php';

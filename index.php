<?php
/******************************************************************************
newdsb
===============================================================================
File:                       index.php
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
check_login_member('all');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$tpl->set_file('content','index.html');

$search_loop=create_search_form($basic_search_fields);

$tplvars['title']=$tplvars['sitename'];
$tpl->set_loop('search',$search_loop);
$tpl->set_var('tplvars',$tplvars);
echo $tpl->process('','content',TPL_FINISH | TPL_OPTIONAL | TPL_LOOP | TPL_INCLUDE);
?>
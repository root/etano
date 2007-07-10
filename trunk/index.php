<?php
/******************************************************************************
Etano
===============================================================================
File:                       index.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

define('CACHE_LIMITER','private');
require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('all');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$tpl->set_file('content','index.html');

$search_loop=create_search_form($basic_search_fields);

$tplvars['title']=$tplvars['sitename'];
$tpl->set_loop('search',$search_loop);
$tpl->set_var('tplvars',$tplvars);
$tpl->process('content','content',TPL_OPTIONAL | TPL_LOOP | TPL_INCLUDE);
if (!empty($GLOBALS['page_last_modified_time'])) {
//	header('Expires: '. gmdate('D,d M Y H:i:s',time()+1209600).' GMT',true);	// +14 days
//	header('Expires: -1',true);
	header('Cache-Control: private, max-age=0',true);
	header('Last-Modified: '. gmdate('D,d M Y H:i:s',$page_last_modified_time).' GMT',true);
}
echo $tpl->process('content','content',TPL_FINISH);
?>
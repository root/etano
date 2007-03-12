<?php
/******************************************************************************
newdsb
===============================================================================
File:                       home.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(3);

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

$user_stats=get_module_stats(1,$_SESSION['user']['user_id']);

$tpl->set_file('content','home.html');
$tpl->set_var('user_stats',$user_stats);
$tpl->process('content','content');

$tplvars['title']='Member Home';
include 'frame.php';
?>
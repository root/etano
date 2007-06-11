<?php
/******************************************************************************
newdsb
===============================================================================
File:                       blogs.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member('all');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$tpl->set_file('content','blogs.html');
$tpl->process('content','content');

$tplvars['title']='Browse Blogs';
$tplvars['page_title']='Blogs';
$tplvars['page']='blogs';
$tplvars['css']='blogs.css';
if (is_file('blogs_left.php')) {
	include 'blogs_left.php';
}
include 'frame.php';
?>
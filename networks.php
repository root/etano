<?php
/******************************************************************************
newdsb
===============================================================================
File:                       networks.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
require_once 'includes/network_functions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(18);

$tpl=new phemplate(_BASEPATH_.'/skins/'.get_my_skin().'/','remove_nonjs');

$uid=0;
$user='';
if (isset($_GET['uid']) && !empty($_GET['uid'])) {
	$uid=(int)$_GET['uid'];
	$user=get_user_by_userid($uid);
} elseif (isset($_GET['user']) && !isset($_GET['uid'])) {
	$user=sanitize_and_format($_GET['user'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
	$uid=get_userid_by_user($user);
} elseif (isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id'])) {
	$uid=$_SESSION['user']['user_id'];
	$user=$_SESSION['user']['user'];
} else {
	redirect2page('index.php');
}

$query="SELECT `net_id`,`network` FROM `{$dbtable_prefix}networks`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$networks=array();
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['network']=sanitize_and_format($rsrow['network'],TYPE_STRING,$__html2format[TEXT_DB2DISPLAY]);
	$rsrow['members']=get_cache_user_mini(get_network_members($uid,$rsrow['net_id'],4),get_my_skin(),true);
	if (!empty($rsrow['members'])) {
		$rsrow['see_all']=true;
		$networks[]=$rsrow;
	}
}

$tpl->set_file('content','networks.html');
$tpl->set_loop('networks',$networks);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_NOLOOP);
$tpl->drop_loop('networks');

$tplvars['title']=sprintf('%1s network members',$user);
include 'frame.php';
?>
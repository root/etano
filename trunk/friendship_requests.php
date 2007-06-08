<?php
/******************************************************************************
newdsb
===============================================================================
File:                       friendship_requests.php
$Revision: 109 $
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
check_login_member('manage_networks');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$query="SELECT a.`nconn_id`,a.`fk_user_id`,b.`_user` as `user`,c.`network` FROM `{$dbtable_prefix}user_networks` a,`{$dbtable_prefix}user_profiles` b,`{$dbtable_prefix}networks` c WHERE a.`fk_user_id`=b.`fk_user_id` AND a.`fk_net_id`=c.`net_id` AND a.`fk_user_id_other`='".$_SESSION['user']['user_id']."' AND a.`nconn_status`=0";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$loop=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['network']=sanitize_and_format($rsrow['network'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$loop[]=$rsrow;
}

$output['return']='friendship_requests.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','friendship_requests.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Friendship Requests';
$tplvars['page_title']='Friendship Requests';
$tplvars['page']='friendship_requests';
$tplvars['css']='friendship_requests.css';
if (is_file('friendship_requests_left.php')) {
	include 'friendship_requests_left.php';
}
include 'frame.php';
?>
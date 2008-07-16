<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_networks.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/network_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/net.inc.php';
check_login_member('manage_networks');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');

require _BASEPATH_.'/includes/classes/user_cache.class.php';
$user_cache=new user_cache(get_my_skin());

$query="SELECT `net_id`,`network` FROM `{$dbtable_prefix}networks`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$networks=array();
$i=0;
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['network']=sanitize_and_format($rsrow['network'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$net_members=get_network_members($_SESSION[_LICENSE_KEY_]['user']['user_id'],$rsrow['net_id'],4);
	if (!empty($net_members)) {
		$rsrow['members']=$user_cache->get_cache_tpl($net_members,'result_user');
	}
	if (!empty($rsrow['members'])) {
		$rsrow['see_all']=true;
		$networks[]=$rsrow;
	}
}

$output=array();
$output['user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
$tpl->set_file('content','my_networks.html');
$tpl->set_var('output',$output);
$tpl->set_loop('networks',$networks);
$tpl->process('content','content',TPL_MULTILOOP | TPL_OPTLOOP | TPL_NOLOOP);
$tpl->drop_loop('networks');
unset($networks);

$tplvars['title']=$GLOBALS['_lang'][240];
$tplvars['page_title']=$GLOBALS['_lang'][240];
$tplvars['page']='my_networks';
$tplvars['css']='my_networks.css';
if (is_file('my_networks_left.php')) {
	include 'my_networks_left.php';
}
unset($page_last_modified_time);	// we want everything fresh on this page.
include 'frame.php';

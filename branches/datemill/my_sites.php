<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_sites.php
$Revision: 320 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$from="`user_sites`";
$where="`fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];

$loop=array();
$query="SELECT `site_id`,`license`,`license_md5`,`baseurl`,`is_featured`,`screenshot` FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	if (empty($rsrow['screenshot'])) {
		$rsrow['screenshot']='No';
	} else {
		$rsrow['screenshot']='<img src="'.$tplvars['photourl'].'/sites/'.$rsrow['screenshot'].'" />';
	}
	if (empty($rsrow['baseurl'])) {
		$rsrow['baseurl']='<a href="site_edit.php?site_id='.$rsrow['site_id'].'" onclick="return assign_site(\''.$rsrow['license'].'\')">Assign now</a>';
	} else {
		$rsrow['baseurl']='<a target="_blank" href="'.$rsrow['baseurl'].'">'.$rsrow['baseurl'].'</a>';
	}
	$rsrow['is_featured']=(!empty($rsrow['is_featured']) && !empty($rsrow['screenshot']) && !empty($rsrow['baseurl'])) ? 'Yes' : 'No';
	$loop[]=$rsrow;
}

$output['return2me']='my_sites.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_sites.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='My Site Licenses';
$tplvars['page_title']='My Site Licenses';
$tplvars['page']='my_sites';
$tplvars['css']='my_sites.css';
if (is_file('my_sites_left.php')) {
	include 'my_sites_left.php';
}
include 'frame2.php';

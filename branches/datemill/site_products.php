<?php
/******************************************************************************
Etano
===============================================================================
File:                       site_products.php
$Revision: 290 $
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
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$lk=sanitize_and_format_gpc($_GET,'lk',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

if (empty($lk)) {
	redirect2page('my_sites.php');
}

$query="SELECT `site_id`,`baseurl`,`license`,`license_md5` FROM `user_sites` WHERE `license_md5`='$lk' AND `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
	if (empty($output['baseurl'])) {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='In order to have access to your products you need to enter your site URL first.';
		redirect2page('site_edit.php',$topass,'site_id='.$output['site_id']);
	}
} else {
	// problem, why don't we have this license in our database?
	// should inform admin about this.
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Sorry, there has been a problem fetching the available products for your site. Please inform the administrator about this.';
	redirect2page('contact.php',$topass);
}

$loop=array();
$query="SELECT b.`prod_id`,b.`prod_name`,b.`version` FROM `user_products` a,`products` b WHERE a.`fk_prod_id`=b.`prod_id` AND a.`fk_site_id`=".$output['site_id']." AND a.`fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['user_id'];
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['prod_name']=sanitize_and_format($rsrow['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$loop[]=$rsrow;
}

$output['return2me']='site_products.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','site_products.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Etano - my products';
$tplvars['page_title']='My Products';
$tplvars['page']='site_products';
$tplvars['css']='site_products.css';
if (is_file('site_products_left.php')) {
	include 'site_products_left.php';
}
include 'frame.php';

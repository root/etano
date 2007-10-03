<?php
/******************************************************************************
Etano
===============================================================================
File:                       site_updates.php
$Revision: 290 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

/* retrieve a list of available updates that require some of my installed modules or that don't require anything at all */

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

$query="SELECT `site_id`,`baseurl`,`license_md5` FROM `user_sites` WHERE `license_md5`='$lk'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
if (mysql_num_rows($res)) {
	$output=mysql_fetch_assoc($res);
	if (empty($output['baseurl'])) {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please enter your site URL first.';
		redirect2page('site_edit.php',$topass,'site_id='.$output['site_id'].'&return=site_updates.php');
	}
} else {
	// problem, why don't we have this license in our database?
	// should inform admin about this.
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='Sorry, there has been a problem fetching the available updates for your site. Please inform the administrator about this.';
	redirect2page('contact.php',$topass);
}

$my_modules=array();
$query="SELECT `module_code`,`version` FROM `user_site_modules` WHERE `fk_site_id`=".$output['site_id']." AND `is_legit`=1";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$my_modules[$rsrow['module_code']]=$rsrow['version'];
}

$output['last_sync']='never';
$temp=get_user_stats($_SESSION[_LICENSE_KEY_]['user']['user_id'],'last_sync');
if (!empty($temp['last_sync'])) {
	$output['last_sync']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['datetime_format'],$temp['last_sync']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
}

// fetch all available updates and their requirements from db
$query="SELECT a.`update_id`,a.`update_name`,a.`update_diz`,b.`module_code`,b.`version`,b.`min-version`,b.`max-version` FROM `updates` a LEFT JOIN `update_requirements` b ON a.`update_id`=b.`fk_update_id`";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$update_reqs=array();
$updates=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$updates[$rsrow['update_id']]['update_name']=$rsrow['update_name'];
	$updates[$rsrow['update_id']]['update_diz']=$rsrow['update_diz'];
	unset($rsrow['update_name'],$rsrow['update_diz']);
	$rsrow['version']=(float)$rsrow['version'];
	$rsrow['min-version']=(float)$rsrow['min-version'];
	$rsrow['max-version']=(float)$rsrow['max-version'];
	if (!empty($rsrow['module_code'])) {
		$update_reqs[$rsrow['update_id']][]=$rsrow;
	}
}

$temp=array();
foreach ($updates as $update_id=>$v) {
	$req_ok=true;
	// for all requirements of this update...see if we match
	for ($i=0;isset($update_reqs[$update_id][$i]);++$i) {
		$required=$update_reqs[$update_id][$i];
		if (!isset($my_modules[$required['module_code']])) {
			$req_ok=false;
			break;
		} elseif (!empty($required['version']) && ((float)$my_modules[$required['module_code']])!=((float)$required['version'])) {
			$req_ok=false;
			break;
		} elseif (!empty($required['min-version']) && ((float)$my_modules[$required['module_code']])<((float)$required['min-version'])) {
			$req_ok=false;
			break;
		} elseif (!empty($required['max-version']) && ((float)$my_modules[$required['module_code']])>((float)$required['max-version'])) {
			$req_ok=false;
			break;
		}
	}
	if ($req_ok) {
		$temp[$update_id]=1;
	}
}

$where="`update_id` IN ('".join("','",array_keys($temp))."')";
$from="`updates`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `update_id`,`update_name`,`update_diz`,UNIX_TIMESTAMP(`last_changed`) as `last_changed` FROM $from WHERE $where ORDER BY `update_id` LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['last_changed']=strftime($_SESSION[_LICENSE_KEY_]['user']['prefs']['date_format'],$rsrow['last_changed']+$_SESSION[_LICENSE_KEY_]['user']['prefs']['time_offset']);
		$rsrow['update_name']=sanitize_and_format($rsrow['update_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['update_diz']=sanitize_and_format($rsrow['update_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['enc_url']=rawurlencode(_BASEURL_.'/remote/download.php?t=u&id='.$rsrow['update_id'].'&lk='.$output['license_md5']);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='site_updates.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','site_updates.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);
$tpl->drop_var('output.pager2');

$tplvars['title']='Etano - updates';
$tplvars['page_title']='Etano Updates';
$tplvars['page']='site_updates';
$tplvars['css']='site_updates.css';
if (is_file('site_updates_left.php')) {
	include 'site_updates_left.php';
}
include 'frame2.php';

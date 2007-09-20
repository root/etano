<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_updates.php
$Revision: 290 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

/* retrieve a list of available addons that require some of my installed modules and I don't already have */

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
check_login_member('auth');

$dbtable_prefix='';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

if (empty($_SESSION['user']['my_modules'])) {
	$query="SELECT `module_code`,`version` FROM `user_installed_modules` WHERE `fk_user_id`=".$_SESSION['user']['user_id'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$_SESSION['user']['my_modules'][$rsrow['module_code']]=$rsrow['version'];
	}
}

if (empty($_SESSION['user']['last_sync'])) {
	$output['last_sync']='Never';
	$query="SELECT `value` FROM `dsb_user_stats` WHERE `fk_user_id`=".$_SESSION['user']['user_id']." AND `stat`='last_sync'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output['last_sync']=strftime($_SESSION['user']['prefs']['datetime_format'],(int)mysql_result($res,0,0)+$_SESSION['user']['prefs']['time_offset']);
	}
	$_SESSION['user']['last_sync']=$output['last_sync'];
} else {
	$output['last_sync']=$_SESSION['user']['last_sync'];
}

// just retrieve the modules which MIGHT require one of my installed modules
$query="SELECT a.`fk_addon_id`,a.`module_code` as `req_module_code`,a.`version`,a.`min-version`,a.`max-version`,b.`module_code`,b.`version` FROM `addon_requirements` a,`addons` b WHERE a.`fk_addon_id`=b.`addon_id` AND a.`module_code` IN ('".join("','",array_keys($_SESSION['user']['my_modules'])).")";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$addon_reqs=array();
$addons=array();
while ($rsrow=mysql_fetch_assoc($res)) {
	$addons[$rsrow['fk_addon_id']]['module_code']=$rsrow['module_code'];
	$addons[$rsrow['fk_addon_id']]['version']=$rsrow['version'];
	unset($rsrow['module_code'],$rsrow['version']);
	$addon_reqs[$rsrow['fk_addon_id']][]=$rsrow;
}

$temp=array();
foreach ($addon_reqs as $k=>$v) {
	$req_ok=true;
	for ($i=0;isset($v[$i]);++$i) {	// check all requirements of this addon
		if (!empty($v[$i]['version']) && $_SESSION['user']['my_modules'][$v[$i]['req_module_code']]!=$v[$i]['version']) {
			$req_ok=false;
			break;
		} elseif (!empty($v[$i]['min-version']) && $_SESSION['user']['my_modules'][$v[$i]['req_module_code']]<$v[$i]['min-version']) {
			$req_ok=false;
			break;
		} elseif (!empty($v[$i]['max-version']) && $_SESSION['user']['my_modules'][$v[$i]['req_module_code']]>$v[$i]['max-version']) {
			$req_ok=false;
			break;
		}		
	}
	if ($req_ok && (!isset($_SESSION['user']['my_modules'][$addons[$k]['module_code']]) || $_SESSION['user']['my_modules'][$addons[$k]['module_code']]<$addons[$k]['version'])) {
		$temp[$k]=1;
	}
}

$where="`addon_id` IN ('".join("','",$temp)."')";
$from="`{$dbtable_prefix}addons`";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$query="SELECT `addon_id`,`addon_name`,`addon_diz`,`addon_pic`,`version`,UNIX_TIMESTAMP(`last_changed`) as `last_changed`,`price` FROM $from WHERE $where ORDER BY `last_changed` DESC LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['last_changed']=strftime($_SESSION['user']['prefs']['datetime_format'],$rsrow['last_changed']+$_SESSION['user']['prefs']['time_offset']);
		$rsrow['addon_name']=sanitize_and_format($rsrow['addon_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['addon_diz']=sanitize_and_format($rsrow['addon_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if (!is_file(_PHOTOPATH_.'/addons/'.$rsrow['addon_pic'])) {
			$rsrow['addon_pic']='no_photo.gif';
		}
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='addons.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.str_replace('&','&amp;',$_SERVER['QUERY_STRING']);
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_updates.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTLOOP | TPL_OPTIONAL | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);
$tpl->drop_var('output.pager2');

$tplvars['title']='Etano - networking addons';
$tplvars['page_title']='Etano Addons';
$tplvars['page']='addons';
$tplvars['css']='addons.css';
if (is_file('addons_left.php')) {
	include 'addons_left.php';
}
include 'frame.php';

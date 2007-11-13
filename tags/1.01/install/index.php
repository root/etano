<?php
/******************************************************************************
Etano
===============================================================================
File:                       install/index.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/sco_functions.inc.php';
require_once '../includes/classes/phemplate.class.php';

$output=array();
$rw_files=array();
$i=0;
$rw_files[$i]['file']='includes/access_levels.inc.php';
$rw_files[$i]['type']='File';
$rw_files[$i]['perms']='666';
$rw_files[$i]['real_file']=array('includes/access_levels.inc.php');
++$i;
$rw_files[$i]['file']='includes/banned_words.inc.php';
$rw_files[$i]['type']='File';
$rw_files[$i]['perms']='666';
$rw_files[$i]['real_file']=array('includes/banned_words.inc.php');
++$i;
$rw_files[$i]['file']='includes/defines.inc.php';
$rw_files[$i]['type']='File';
$rw_files[$i]['perms']='666';
$rw_files[$i]['real_file']=array('includes/defines.inc.php');
++$i;
$rw_files[$i]['file']='includes/fields.inc.php';
$rw_files[$i]['type']='File';
$rw_files[$i]['perms']='666';
$rw_files[$i]['real_file']=array('includes/fields.inc.php');
++$i;
$rw_files[$i]['file']='includes/site_bans.inc.php';
$rw_files[$i]['type']='File';
$rw_files[$i]['perms']='666';
$rw_files[$i]['real_file']=array('includes/site_bans.inc.php');
++$i;

$rw_files[$i]['file']='cache/blogs/1-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('cache/blogs/1','cache/blogs/2','cache/blogs/3','cache/blogs/4','cache/blogs/5','cache/blogs/6','cache/blogs/7','cache/blogs/8','cache/blogs/9');
++$i;

$rw_files[$i]['file']='cache/blogs/posts/1-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('cache/blogs/posts/1','cache/blogs/posts/2','cache/blogs/posts/3','cache/blogs/posts/4','cache/blogs/posts/5','cache/blogs/posts/6','cache/blogs/posts/7','cache/blogs/posts/8','cache/blogs/posts/9');
++$i;

$rw_files[$i]['file']='media/pics/0-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('media/pics/0','media/pics/1','media/pics/2','media/pics/3','media/pics/4','media/pics/5','media/pics/6','media/pics/7','media/pics/8','media/pics/9');
++$i;

$rw_files[$i]['file']='media/pics/t1/0-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('media/pics/t1/0','media/pics/t1/1','media/pics/t1/2','media/pics/t1/3','media/pics/t1/4','media/pics/t1/5','media/pics/t1/6','media/pics/t1/7','media/pics/t1/8','media/pics/t1/9');
++$i;

$rw_files[$i]['file']='media/pics/t2/0-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('media/pics/t2/0','media/pics/t2/1','media/pics/t2/2','media/pics/t2/3','media/pics/t2/4','media/pics/t2/5','media/pics/t2/6','media/pics/t2/7','media/pics/t2/8','media/pics/t2/9');
++$i;

$rw_files[$i]['file']='rss';
$rw_files[$i]['type']='Folder';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('rss');
++$i;

$rw_files[$i]['file']='skins_site/def/cache/users/1-9';
$rw_files[$i]['type']='All folders';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('skins_site/def/cache/users/1','skins_site/def/cache/users/2','skins_site/def/cache/users/3','skins_site/def/cache/users/4','skins_site/def/cache/users/5','skins_site/def/cache/users/6','skins_site/def/cache/users/7','skins_site/def/cache/users/8','skins_site/def/cache/users/9');
++$i;

$rw_files[$i]['file']='skins_site/def/cache/widgets';
$rw_files[$i]['type']='Folder';
$rw_files[$i]['perms']='777';
$rw_files[$i]['real_file']=array('skins_site/def/cache/widgets');
++$i;

$tpl=new phemplate('skin/','remove_nonjs');
$tpl->set_file('content','index.html');

$error=false;
$topass=array();
// DETECT ENVIRONMENT
	// check php version
if (function_exists('version_compare')) {
	if (version_compare(phpversion(),'4.4.0')>=0) {
		$output['phpversion']='state_green';
	} else {
		$error=true;
		$output['phpversion']='state_red';
	}
} else {
	$error=true;
	$output['phpversion']='state_red';
}

if (function_exists('mysql_connect')) {
	$output['mysql']='state_green';
} else {
	$error=true;
	$output['mysql']='state_red';
}

if (function_exists('mail')) {
	$output['mail']='state_green';
} else {
	$error=true;
	$output['mail']='state_red';
}

if (extension_loaded('gd') && function_exists('gd_info')) {
	$temp=gd_info();
	$temp['GD Version']=preg_replace('/[^0-9]/','',$temp['GD Version']);
	if (((int)$temp['GD Version'])>=((int)str_pad('2',strlen($temp['GD Version'])-1,'0',STR_PAD_RIGHT))) {
		$output['gd2']='state_green';
	} else {
		$error=true;
		$output['gd2']='state_red';
	}
	if (!isset($temp['FreeType Support']) && $temp['FreeType Support']) {
		$output['gd2type']='state_green';
	} else {
		$output['gd2type']='state_yellow';
	}
} else {
	$error=true;
	$output['gd2']='state_red';
}

if (php_sapi_name()=='apache') {
	$output['sapi']='state_green';
} else {
	$output['sapi']='state_yellow';
}

$fp=@fopen(dirname(__FILE__).'/write_test.txt','wb');
if ($fp) {
	$temp=@fwrite($fp,'test');
	fclose($fp);
	if ($temp) {
		$_SESSION['install']['write']='disk';
//		$output['write']='<span class="comment">(direct write)</span><img src="skin/images/check.gif" alt="ok" />';
		$output['write']='state_green';
		$rw_files=array();
		@unlink(dirname(__FILE__).'/write_test.txt');
	}
} elseif (function_exists('ftp_connect')) {
	$_SESSION['install']['write']='ftp';
//	$output['write']='<span class="comment">(ftp method)</span><img src="skin/images/check.gif" alt="ok" />';
	$output['write']='state_green';
	$rw_files=array();
} else {
	$_SESSION['install']['write']='disk';
	$output['show_rw']=true;
	$output['write']='state_green';

	$basepath=dirname(__FILE__).'/../';
	$local_error=false;
	for ($i=0;isset($rw_files[$i]);++$i) {
		$local_error=false;
		for ($j=0;isset($rw_files[$i]['real_file'][$j]);++$j) {
			if (substr(sprintf('%o',fileperms($basepath.$rw_files[$i]['real_file'][$j])),-3)!=$rw_files[$i]['perms']) {
				$local_error=true;
				break;
			}
		}
		if ($local_error) {
			$error=true;
			$rw_files[$i]['check_result']='state_red';
			$output['write']='state_red';
		} else {
			$rw_files[$i]['check_result']='state_green';
		}
		unset($rw_files[$i]['real_file']);
	}
}

$temp=substr(sprintf('%o',fileperms(dirname(__FILE__).'/../tmp/')),-3);
if ($temp=='777') {
	$output['tmp_perm']='state_green';
} else {
	$error=true;
	$output['tmp_perm']='state_red';
}

$temp=substr(sprintf('%o',fileperms(dirname(__FILE__).'/../tmp/admin/')),-3);
if ($temp=='777') {
	$output['tmpadmin_perm']='state_green';
} else {
	$error=true;
	$output['tmpadmin_perm']='state_red';
}

// check if we can use any exec function to find out where php-cli and mysql binaries are
$temp='';
if (function_exists('exec')) {
	$temp=@exec('which php');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['phpbin']=$temp;
		$_SESSION['install']['exec']='exec';
	}
	$temp=@exec('which mysql');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['mysqlbin']=$temp;
		$_SESSION['install']['exec']='exec';
	}
}
if (empty($temp) && function_exists('shell_exec')) {
	$temp=@shell_exec('which php');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['phpbin']=$temp;
		$_SESSION['install']['exec']='shell_exec';
	}
	$temp=@shell_exec('which mysql');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['mysqlbin']=$temp;
		$_SESSION['install']['exec']='shell_exec';
	}
}
if (empty($temp) && function_exists('system')) {
	$temp=@system('which php');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['phpbin']=$temp;
		$_SESSION['install']['exec']='system';
	}
	$temp=@system('which mysql');
	if (!empty($temp) && $temp{0}=='/') {
		$_SESSION['install']['mysqlbin']=$temp;
		$_SESSION['install']['exec']='system';
	}
}
if (empty($temp) && function_exists('popen')) {
	$fp=@popen('which php','r');
	if ($fp) {
		$temp=fgets($fp);
		if (!empty($temp) && $temp{0}=='/') {
			$_SESSION['install']['phpbin']=trim($temp);
			$_SESSION['install']['exec']='popen';
		}
		@pclose($fp);
		$fp=@popen('which mysql','r');
		$temp=fgets($fp);
		if (!empty($temp) && $temp{0}=='/') {
			$_SESSION['install']['mysqlbin']=trim($temp);
			$_SESSION['install']['exec']='popen';
		}
		@pclose($fp);
	}
}

if (!$error) {
	$output['continue']=true;
}

$output['rand']=mt_rand(1,10000);
$tplvars=array();
$tplvars['page_title']='Etano Install Process';
$tplvars['css']='index.css';
$tplvars['page']='index';
$tpl->set_var('output',$output);
$tpl->set_var('tplvars',$tplvars);
$tpl->set_loop('rw_files',$rw_files);
$tpl->process('content','content',TPL_OPTIONAL | TPL_LOOP);
include 'frame.php';

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/package_install.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/classes/fileop.class.php';
require_once '../includes/classes/etano_package.class.php';
allow_dept(DEPT_ADMIN);

$error=false;
$tpl=new phemplate('skin/','remove_nonjs');
$output=array();
$fileop=new fileop();

$file=sanitize_and_format_gpc($_GET,'f',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');

if (substr($file,0,7)=='http://') {
	// save it in tmp/packages and rename $file to filename.zip
	require_once '../includes/classes/package_downloader.class.php';
	$p=new package_downloader($file);
	if ($p->download()) {
		$file=$p->file;
	} else {
		$file='';
	}
}

// read the manifest
if (substr($file,-4)=='.zip') {
	$dirname=$fileop->extract_archive(_BASEPATH_.'/tmp/packages/'.$file);

	$p=new etano_package();
	if (is_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml')) {
		$p->set_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml');
	} else {
		$fileop->delete(_BASEPATH_.'/tmp/packages/'.$dirname);
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Invalid package';
	}

	if (!$p->error) {
		// read currently installed modules
		$query="SELECT `module_code`,`version` FROM `{$dbtable_prefix}modules`";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$mcodes=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			$mcodes[$rsrow['module_code']]=$rsrow['version'];
		}
		// make sure this new package is installable
		if (!isset($mcodes[$p->module_code]) || $mcodes[$p->module_code]<$p->version) {	// not installed packages/versions
			for ($j=0;isset($p->install[$j]);++$j) {
				$req_ok=true;
				for ($k=0;isset($p->install[$j]['requires'][$k]);++$k) {
					if (!isset($mcodes[$p->install[$j]['requires'][$k]['id']]) || (isset($p->install[$j]['requires'][$k]['version']) && $mcodes[$p->install[$j]['requires'][$k]['id']]!=$p->install[$j]['requires'][$k]['version'])) {
						$req_ok=false;
						break;
					}
				}
				if ($req_ok) {	// if all requirements of this install are satisfied....
					if ($p->dry_run($j) {	// ...test to see if we can install the package
						$p->install($j);	// ...and finally install it.
						// if there's another install instruction after this one we need to reread the list of installed
						// modules because our install might have modified it.
						if (isset($p->install[$j+1])) {
							// read currently installed modules
							$query="SELECT `module_code`,`version` FROM `{$dbtable_prefix}modules`";
							if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
							$mcodes=array();
							while ($rsrow=mysql_fetch_assoc($res)) {
								$mcodes[$rsrow['module_code']]=$rsrow['version'];
							}
						}
					}
				} else {
					// some of the requirements of this install are not satisfied, moving on to next install instruction
				}
			}
		}
	} else {
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Error reading the package';
	}
} else {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='File is not a valid Etano package';
}

$tpl->set_file('content','packages.html');
$tpl->set_loop('installed',$installed);
$tpl->set_loop('not_installed',$not_installed);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL | TPL_OPTLOOP);

$tplvars['title']='Package manager';
$tplvars['page']='packages';
$tplvars['css']='packages.css';
include 'frame.php';

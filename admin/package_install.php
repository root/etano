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

$install_index=0;

// read the manifest
if (substr($file,-4)=='.zip') {
	$p=new etano_package();
	if (!isset($_GET['finish'])) {	// no previous error for this package.
		$dirname=$fileop->extract_zip(_BASEPATH_.'/tmp/packages/'.$file);
		if (is_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml')) {
			$p->set_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml');
		} elseif (!empty($dirname)) {
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
				for ($install_index=0;isset($p->install[$install_index]);++$install_index) {
					$req_ok=true;
					for ($k=0;isset($p->install[$install_index]['requires'][$k]);++$k) {
						if (!isset($mcodes[$p->install[$install_index]['requires'][$k]['id']]) || (isset($p->install[$install_index]['requires'][$k]['version']) && $mcodes[$p->install[$install_index]['requires'][$k]['id']]!=$p->install[$install_index]['requires'][$k]['version'])) {
							$req_ok=false;
							break;
						}
					}
					if ($req_ok) {	// if all requirements of this install are satisfied....
						if ($p->dry_run($install_index)) {	// ...test to see if we can install the package
							if ($p->install($install_index)) {	// ...and finally install it.
								// if there's another install instruction after this one we need to reread the list of installed
								// modules because our install might have modified it.
								if (isset($p->install[$install_index+1])) {
									// read currently installed modules
									$query="SELECT `module_code`,`version` FROM `{$dbtable_prefix}modules`";
									if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
									$mcodes=array();
									while ($rsrow=mysql_fetch_assoc($res)) {
										$mcodes[$rsrow['module_code']]=$rsrow['version'];
									}
								}
							} else {
								break;
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
		$dirname=substr($file,0,-4);
		$p->set_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml');
		if (!$p->error) {
			$p->post_install((int)$_GET['finish']);
		}
	}
} else {
	$error=true;
	$topass['message']['type']=MESSAGE_ERROR;
	$topass['message']['text']='File is not a valid Etano package';
}

if ($p->error && !empty($p->manual_actions)) {
	$tpl->set_file('content','package_install.html');
	$tpl->set_loop('manual_actions',$p->manual_actions);
	$output['f']=$file;
	$output['finish']=$install_index;
	$tpl->set_var('output',$output);
	$tpl->process('content','content',TPL_LOOP);

	$tplvars['title']='Package Manager';
	$tplvars['page']='package_install';
	$tplvars['css']='package_install.css';
	include 'frame.php';
} else {
	if (!$error) {
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Package installed successfully';
	}
	redirect2page('admin/packages.php',$topass);
}

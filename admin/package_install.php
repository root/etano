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
set_time_limit(0);
ignore_user_abort(true);

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
		$file=$p->file_name;
	} else {
		$file='';
		$error=true;
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']=$p->error_text;
	}
}

$install_index=0;
$ui_request=false;

$show_finish=true;	// used to figure out if we should show the finish button in case of an error or not.
if (!$error) {
	// read the manifest
	if (substr($file,-4)=='.zip') {
		$p=new etano_package();
		if (!isset($_GET['finish']) && !isset($_GET['skip_input']) && !isset($_GET['ui_error'])) {	// first time here
			$dirname=$fileop->extract_zip(_BASEPATH_.'/tmp/packages/'.$file);
		} else {
			$dirname=substr($file,0,-4);
		}
		if (is_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml')) {
			$p->set_file(_BASEPATH_.'/tmp/packages/'.$dirname.'/manifest.xml');
		} elseif (!empty($dirname)) {
			if (is_dir(_BASEPATH_.'/tmp/packages/'.$dirname)) {
				$fileop->delete(_BASEPATH_.'/tmp/packages/'.$dirname);
			}
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid package';
		}

		if (!$p->error) {
			$install_index_start=0;
			$skip_input=-1;
			if (isset($_GET['finish'])) {	// no previous error for this package.
				$install_index_start=(int)$_GET['finish'];
				$p->post_install($install_index_start);
				++$install_index_start;
			}
			if (isset($_GET['skip_input'])) {	// returned from a user input page.
				$skip_input=(int)$_GET['skip_input'];
				$install_index_start=$skip_input;
			}
			if (isset($_GET['ui_error'])) {	// returned from a user input page.
				$install_index_start=(int)$_GET['ui_error'];
			}
			// read currently installed modules
			$query="SELECT `module_code`,`version`,`module_type` FROM `{$dbtable_prefix}modules`";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$mcodes=array();
			$skins=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$mcodes[$rsrow['module_code']]=$rsrow['version'];
				if ($rsrow['module_type']==MODULE_SKIN) {
					$skins[]=$rsrow['module_code'];
				}
			}
			$query="SELECT `fk_module_code`,`config_value` FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code` IN ('".join("','",$skins)."') AND `config_option`='skin_dir'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$skins=array();
			while ($rsrow=mysql_fetch_assoc($res)) {
				$skins[$rsrow['fk_module_code']]=$rsrow['config_value'];
			}
			// make sure this new package is installable
			if (!isset($mcodes[$p->module_code]) || $mcodes[$p->module_code]<$p->version) {	// not installed packages/versions
				for ($install_index=$install_index_start;isset($p->install[$install_index]);++$install_index) {
					$req_ok=true;
					// see if all requirements are satisfied
					for ($k=0;isset($p->install[$install_index]['requires'][$k]);++$k) {
						$required=$p->install[$install_index]['requires'][$k];
						if (!isset($mcodes[$required['id']]) || (isset($required['version']) && $mcodes[$required['id']]!=$required['version']) || (isset($required['min-version']) && $mcodes[$required['id']]<$required['min-version']) || (isset($required['max-version']) && $mcodes[$required['id']]>$required['max-version'])) {
							$req_ok=false;
							break;
						}
					}
					if ($req_ok) {
						// see if we're not blocked by some module
						for ($k=0;isset($p->install[$install_index]['blockedby'][$k]);++$k) {
							$blockedby=$p->install[$install_index]['blockedby'][$k];
							if (isset($mcodes[$blockedby['id']]) && ((!isset($blockedby['version']) && !isset($blockedby['min-version']) && !isset($blockedby['max-version'])) || (isset($blockedby['version']) && $mcodes[$blockedby['id']]==$blockedby['version']) || (isset($blockedby['min-version']) && $mcodes[$blockedby['id']]>$blockedby['min-version']) || (isset($blockedby['max-version']) && $mcodes[$blockedby['id']]<$blockedby['max-version']))) {
								$req_ok=false;
								break;
							}
						}
					}
					if ($req_ok) {	// if all requirements of this install are satisfied....
						if ($changes=$p->dry_run($install_index)) {	// ...test to see if we can install the package
							// $changes holds most of the files that will be changed
							// "most" because can't read the changes made by 'php' or 'extract' commands
							// in future it would be nice to list the files before install
							if (isset($_GET['show_changes'])) {
								function slash_count($a,$b) {$ca=substr_count($a,'/');$cb=substr_count($b,'/');if ($ca==$cb) {return strcmp($a,$b);}return ($ca<$cb)?-1:1;}
								usort($changes,'slash_count');
								echo join('<br>',$changes);die;
							}
							if ($p->install($install_index,$skip_input)) {	// ...and finally install it.
								if (!empty($p->ui)) {	// oops, need to gather some data from user
									$ui_request=true;
									break;
								} else {
									// if there's another install instruction after this one we need to reread the list of installed
									// modules because our install might have modified it.
									if (isset($p->install[$install_index+1])) {
										// read currently installed modules
										$query="SELECT `module_code`,`version`,`module_type` FROM `{$dbtable_prefix}modules`";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										$mcodes=array();
										$skins=array();
										while ($rsrow=mysql_fetch_assoc($res)) {
											$mcodes[$rsrow['module_code']]=$rsrow['version'];
											if ($rsrow['module_type']==MODULE_SKIN) {
												$skins[]=$rsrow['module_code'];
											}
										}
										$query="SELECT `fk_module_code`,`config_value` FROM `{$dbtable_prefix}site_options3` WHERE `fk_module_code` IN ('".join("','",$skins)."') AND `config_option`=`skin_dir`";
										if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
										$skins=array();
										while ($rsrow=mysql_fetch_assoc($res)) {
											$skins[$rsrow['fk_module_code']]=$rsrow['config_value'];
										}
									}
								}
							} else {	// this is bad: this is an error that was not caught by dry_run()
								break;
							}
						} else {
							// dry_run() returned errors so we shouldn't show the finish button
							$show_finish=false;
						}
					} else {
						// some of the requirements of this install are not satisfied, moving on to next install instruction
						// this shouldn't be executed but just to be sure:
						break;
					}
				}
				if (!$ui_request && !$p->error) {
					$p->finish();
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
}

if (isset($p) && $p->error && !empty($p->manual_actions)) {
	$tpl->set_file('content','package_install.html');
	$tpl->set_loop('manual_actions',$p->manual_actions);
	$output['f']=$file;
	$output['finish']=$install_index;
	$output['show_finish']=$show_finish;
	$tpl->set_var('output',$output);
	$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);

	$tplvars['title']='Package Manager';
	$tplvars['page']='package_install';
	$tplvars['css']='package_install.css';
	include 'frame.php';
} elseif ($ui_request) {
	$tpl->set_file('content','package_ui.html');
	$tpl->set_var('output',$p->ui);
	$tpl->process('content','content');

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

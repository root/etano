<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/updates_addedit.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/classes/etano_package.class.php';
require_once '../../includes/classes/zip.class.php';
require_once '../../includes/classes/fileop.class.php';
allow_dept(DEPT_ADMIN);

$updates_default['defaults']=array('update_id'=>0,'update_name'=>'','update_diz'=>'','filename'=>'');
$found=false;
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='updates.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$fileop=new fileop();
	$input=array();
// get the input we need and sanitize it
	$filename=upload_file(_BASEPATH_.'/tmp','filename');
	if (!empty($filename)) {
		$zipfile=new zipfile();
		$zipfile->read_zip(_BASEPATH_.'/tmp/'.$filename);
		$manifest_content='';
		foreach ($zipfile->files as $zfile) {
			if ($zfile['name']=='manifest.xml' && $zfile['dir']=='/') {
				$found=true;
				$manifest_content=$zfile['data'];
				break;
			}
		}
		if ($found) {
			$p=new etano_package();
			$p->_set_content($manifest_content);
			$input['update_name']=$p->module_name;
			$input['filename']=$p->module_code.$p->version.'.zip';
			$requires=array();
			for ($i=0;isset($p->install[0]['requires'][$i]);++$i) {
				$requires[]=$p->install[0]['requires'][$i];
			}
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid package file';
			$fileop->delete(_BASEPATH_.'/tmp/'.$filename);
		}
	}

	$input['update_id']=isset($_POST['update_id']) ? (int)$_POST['update_id'] : 0;
	$input['update_diz']=sanitize_and_format_gpc($_POST,'update_diz',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	if (empty($input['update_diz']) && isset($p->install[0]['text'])) {
		$input['update_diz']=sanitize_and_format($p->install[0]['text'],TYPE_STRING,FORMAT_ADDSLASH);
	}

	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		$now=gmdate('YmdHis');
		if (!empty($input['update_id'])) {
			if ($found) {
				$input['filename']=$input['update_id'].$input['filename'];
				if (is_file(_BASEPATH_.'/dafilez/updates/'.$input['filename'])) {
					$fileop->delete(_BASEPATH_.'/dafilez/updates/'.$input['filename']);
				}
				if (!$fileop->rename(_BASEPATH_.'/tmp/'.$filename,_BASEPATH_.'/dafilez/updates/'.$input['filename'])) {
					$error=true;
					$topass['message']['type']=MESSAGE_ERROR;
					$topass['message']['text']='Could not move the package to final destination';
					$fileop->delete(_BASEPATH_.'/tmp/'.$filename);
				}
			}
			if (!$error) {
				$query="UPDATE `updates` SET `last_changed`='$now'";
				foreach ($updates_default['defaults'] as $k=>$v) {
					if (isset($input[$k])) {
						$query.=",`$k`='".$input[$k]."'";
					}
				}
				$query.=" WHERE `update_id`=".$input['update_id'];
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='Update changed.';
			}
		} else {
			if ($found && !$error) {
				unset($updates_default['defaults']['filename']);
				$query="INSERT INTO `updates` SET `last_changed`='$now'";
				foreach ($updates_default['defaults'] as $k=>$v) {
					if (isset($input[$k])) {
						$query.=",`$k`='".$input[$k]."'";
					}
				}
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$input['update_id']=mysql_insert_id();
				$input['filename']=$input['update_id'].$input['filename'];
				if (is_file(_BASEPATH_.'/dafilez/updates/'.$input['filename'])) {
					$fileop->delete(_BASEPATH_.'/dafilez/updates/'.$input['filename']);
				}
				if ($fileop->rename(_BASEPATH_.'/tmp/'.$filename,_BASEPATH_.'/dafilez/updates/'.$input['filename'])) {
					$query="UPDATE `updates` SET `filename`='".$input['filename']."' WHERE `update_id`=".$input['update_id'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				} else {
					$error=true;
					$topass['message']['type']=MESSAGE_ERROR;
					$topass['message']['text']='Could not move the package to final destination';
					$fileop->delete(_BASEPATH_.'/tmp/'.$filename);
					$query="DELETE FROM `updates` WHERE `update_id`=".$input['update_id'];
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				}
			}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Update added.';
		}
	}
	if (!$error) {
		if (isset($requires)) {
			$query="DELETE FROM `update_requirements` WHERE `fk_update_id`=".$input['update_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($requires)) {
				$query="INSERT INTO `update_requirements` (`fk_update_id`,`module_code`,`version`,`min-version`,`max-version`) VALUES ";
				for ($i=0;isset($requires[$i]);++$i) {
					if (!isset($requires[$i]['version'])) {
						$requires[$i]['version']=0;
					}
					if (!isset($requires[$i]['min-version'])) {
						$requires[$i]['min-version']=0;
					}
					if (!isset($requires[$i]['max-version'])) {
						$requires[$i]['max-version']=0;
					}
					$query.="(".$input['update_id'].",'".$requires[$i]['id']."',".$requires[$i]['version'].",".$requires[$i]['min-version'].",".$requires[$i]['max-version']."),";
				}
				$query=substr($query,0,-1);
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
	} else {
		$nextpage='updates_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['update_diz']=addslashes_mq($_POST['update_diz']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

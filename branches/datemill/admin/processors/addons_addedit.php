<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/addons_addedit.php
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

$dbtable_prefix='';

$addons_default['defaults']=array('addon_id'=>0,'addon_type'=>0,'module_code'=>'','addon_name'=>'','addon_diz'=>'','addon_pic'=>'','version'=>0,'fk_dev_id'=>0,'price'=>0,'filename'=>'');
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='addons.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$fileop=new fileop();
	$input=array();
// get the input we need and sanitize it
	$input['module_code']=sanitize_and_format_gpc($_POST,'module_code',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

	$filename=upload_file(_BASEPATH_.'/tmp','filename');
	if (!empty($filename)) {
		$zipfile=new zipfile();
		$zipfile->read_zip(_BASEPATH_.'/tmp/'.$filename);
		$found=false;
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
			$input['addon_type']=$p->module_type;
			$input['module_code']=$p->module_code;
			$input['addon_name']=$p->module_name;
			$input['version']=$p->version;
			$input['filename']=$input['module_code'].$input['version'].'.zip';
			$requires=array();
			for ($i=0;isset($p->install[0]['requires'][$i]);++$i) {
				$requires[]=$p->install[0]['requires'][$i];
			}
			if (is_file(_BASEPATH_.'/dafilez/addons/'.$input['filename'])) {
				$fileop->delete(_BASEPATH_.'/dafilez/addons/'.$input['filename']);
			}
			if (!$fileop->rename(_BASEPATH_.'/tmp/'.$filename,_BASEPATH_.'/dafilez/addons/'.$input['filename'])) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Could not move the package to final destination';
				$fileop->delete(_BASEPATH_.'/tmp/'.$filename);
			}
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Invalid package file';
			$fileop->delete(_BASEPATH_.'/tmp/'.$filename);
		}
	}

	$input['addon_id']=isset($_POST['addon_id']) ? (int)$_POST['addon_id'] : 0;
	$input['fk_dev_id']=isset($_POST['fk_dev_id']) ? (int)$_POST['fk_dev_id'] : 0;
	$input['price']=isset($_POST['price']) ? (float)$_POST['price'] : 0;
	$input['addon_diz']=sanitize_and_format_gpc($_POST,'addon_diz',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
	$addon_pic=upload_file(_BASEPATH_.'/tmp','addon_pic');
	if (!empty($addon_pic)) {
		$ext=strtolower(substr(strrchr($addon_pic,'.'),1));
		$input['addon_pic']=$input['module_code'].'.'.$ext;

		if (is_file(_PHOTOPATH_.'/addons/'.$input['addon_pic'])) {
			$fileop->delete(_PHOTOPATH_.'/addons/'.$input['addon_pic']);
		}
		if (!$fileop->rename(_BASEPATH_.'/tmp/'.$addon_pic,_PHOTOPATH_.'/addons/'.$input['addon_pic'])) {
			unset($input['addon_pic']);
		}
	}

	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		$now=gmdate('YmdHis');
		if (!empty($input['addon_id'])) {
			$query="UPDATE `{$dbtable_prefix}addons` SET `last_changed`='$now'";
			foreach ($addons_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `addon_id`=".$input['addon_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Addon changed.';
		} else {
			$query="INSERT INTO `{$dbtable_prefix}addons` SET `last_changed`='$now'";
			foreach ($addons_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['addon_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Addon added.';
		}
		if (isset($requires)) {
			$query="DELETE FROM `addon_requirements` WHERE `fk_addon_id`=".$input['addon_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (!empty($requires)) {
				$query="INSERT INTO `addon_requirements` (`fk_addon_id`,`module_code`,`version`,`min-version`,`max-version`) VALUES ";
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
					$query.="(".$input['addon_id'].",'".$requires[$i]['id']."',".$requires[$i]['version'].",".$requires[$i]['min-version'].",".$requires[$i]['max-version']."),";
				}
				$query=substr($query,0,-1);
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
	} else {
		$nextpage='addons_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
//		$input['addon_diz']=addslashes_mq($_POST['addon_diz']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}
$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

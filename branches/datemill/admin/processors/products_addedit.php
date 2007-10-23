<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/products_addedit.php
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

$products_default['defaults']=array('prod_id'=>0,'is_visible'=>0,'prod_type'=>0,'module_code'=>'','prod_name'=>'','prod_diz'=>'','prod_pic'=>'','version'=>0,'fk_dev_id'=>0,'price'=>0,'filename'=>'');
$error=false;
$qs='';
$qs_sep='';
$topass=array();
$nextpage='products.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$fileop=new fileop();
	$input=array();
// get the input we need and sanitize it
	$input['module_code']=sanitize_and_format_gpc($_POST,'module_code',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['is_visible']=isset($_POST['is_visible']) ? 1 : 0;
	$input['prod_id']=isset($_POST['prod_id']) ? (int)$_POST['prod_id'] : 0;
	$input['prod_name']=sanitize_and_format_gpc($_POST,'prod_name',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$input['fk_dev_id']=isset($_POST['fk_dev_id']) ? (int)$_POST['fk_dev_id'] : 0;
	$input['price']=isset($_POST['price']) ? (float)$_POST['price'] : 0;
	$input['prod_diz']=sanitize_and_format_gpc($_POST,'prod_diz',TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');

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
			$input['prod_type']=$p->module_type;
			$input['module_code']=$p->module_code;
			if (empty($input['prod_name'])) {
				$input['prod_name']=$p->module_name;
			}
			$input['version']=$p->version;
			$input['filename']=$input['module_code'].$input['version'].'.zip';
			if (is_file(_BASEPATH_.'/dafilez/products/'.$input['filename'])) {
				$fileop->delete(_BASEPATH_.'/dafilez/products/'.$input['filename']);
			}
			if (!$fileop->rename(_BASEPATH_.'/tmp/'.$filename,_BASEPATH_.'/dafilez/products/'.$input['filename'])) {
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

	$prod_pic=upload_file(_BASEPATH_.'/tmp','prod_pic');
	if (!empty($prod_pic)) {
		$ext=strtolower(substr(strrchr($prod_pic,'.'),1));
		$input['prod_pic']=$input['module_code'].'.'.$ext;

		if (is_file(_PHOTOPATH_.'/products/'.$input['prod_pic'])) {
			$fileop->delete(_PHOTOPATH_.'/products/'.$input['prod_pic']);
		}
		if (!$fileop->rename(_BASEPATH_.'/tmp/'.$prod_pic,_PHOTOPATH_.'/products/'.$input['prod_pic'])) {
			unset($input['prod_pic']);
		}
	}

	if (!empty($_POST['return'])) {
		$input['return']=sanitize_and_format_gpc($_POST,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUDECODE,'');
		$nextpage=$input['return'];
	}

	if (!$error) {
		$now=gmdate('YmdHis');
		if (!empty($input['prod_id'])) {
			$query="UPDATE `products` SET `last_changed`='$now'";
			foreach ($products_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			$query.=" WHERE `prod_id`=".$input['prod_id'];
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Product changed.';
		} else {
			$query="INSERT INTO `products` SET `last_changed`='$now'";
			foreach ($products_default['defaults'] as $k=>$v) {
				if (isset($input[$k])) {
					$query.=",`$k`='".$input[$k]."'";
				}
			}
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$input['prod_id']=mysql_insert_id();
			$topass['message']['type']=MESSAGE_INFO;
			$topass['message']['text']='Product added.';
		}
	} else {
		$nextpage='products_addedit.php';
// 		you must re-read all textareas from $_POST like this:
//		$input['x']=addslashes_mq($_POST['x']);
		$input['prod_diz']=addslashes_mq($_POST['prod_diz']);
		$input=sanitize_and_format($input,TYPE_STRING,FORMAT_HTML2TEXT_FULL | FORMAT_STRIPSLASH);
		$topass['input']=$input;
	}
}

$nextpage=_BASEURL_.'/admin/'.$nextpage;
redirect2page($nextpage,$topass,'',true);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_options_del_file.php
$Revision: 610 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
require_once '../../includes/admin_functions.inc.php';
require_once '../../includes/classes/fileop.class.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$error=false;
$topass=array();
$cid=isset($_GET['cid']) ? (int)$_GET['cid'] : 0;

if (!empty($cid)) {
	$query="SELECT `config_value`,`option_type`,`fk_module_code` FROM `{$dbtable_prefix}site_options3` WHERE `config_id`=$cid";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$rsrow=mysql_fetch_assoc($res);
		$qs.=$qs_sep.'module_code='.$rsrow['fk_module_code'];
		$qs_sep='&';
		if ($rsrow['option_type']==FIELD_FILE) {
			$f=new fileop();
			if (!is_file($rsrow['config_value']) || $f->delete($rsrow['config_value'])) {
				$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`='' WHERE `config_id`=$cid";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
				$topass['message']['type']=MESSAGE_INFO;
				$topass['message']['text']='File removed';
			} else {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text']='Unable to remove the file. Permission issues?';
			}
		} else {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text']='Unable to remove the file';
		}
	}
}

if (isset($_GET['silent'])) {
	echo '';
} else {
	redirect2page('admin/site_options.php',$topass,$qs);
}

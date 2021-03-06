<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/site_options.php
$Revision$
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

$error=false;
$qs='';
$qs_sep='';
$topass=array();
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();

	$query="SELECT `config_option`,`option_type`,`fk_module_code`,`choices` FROM `{$dbtable_prefix}site_options3`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		switch ($rsrow['option_type']) {

			case FIELD_CHECKBOX:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD],0);
				break;

			case FIELD_TEXTFIELD:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
				break;

			case FIELD_INT:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_INT,0,0);
				break;

			case FIELD_TEXTAREA:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_STRING,$__field2format[FIELD_TEXTAREA],'');
				break;

			case FIELD_SELECT:
				$input[$rsrow['fk_module_code']][$rsrow['config_option']]=sanitize_and_format_gpc($_POST,$rsrow['fk_module_code'].'_'.$rsrow['config_option'],TYPE_INT,0,0);
				break;

			case FIELD_FILE:
				if (!empty($_FILES[$rsrow['fk_module_code'].'_'.$rsrow['config_option']]['tmp_name'])) {
					if ($temp=upload_file(_BASEPATH_.'/tmp',$rsrow['fk_module_code'].'_'.$rsrow['config_option'])) {
						$f=new fileop();
						if ($f->rename(_BASEPATH_.'/tmp/'.$temp,_BASEPATH_.$rsrow['choices'].'/'.$temp)) {
							$input[$rsrow['fk_module_code']][$rsrow['config_option']]=_BASEPATH_.$rsrow['choices'].'/'.$temp;
						} else {
							$error=true;
						}
					} else {
						$error=true;
					}
				}
				break;

		}
	}

	if (!$error) {
		foreach ($input as $module_code=>$v) {
			foreach ($v as $config_option=>$config_value) {
				$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`='$config_value' WHERE `config_option`='$config_option' AND `fk_module_code`='$module_code'";
				if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			}
		}
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Site features updated.';
	}

	if (isset($_POST['module_code'])) {
		$qs.=$qs_sep.'module_code='.$_POST['module_code'];
	}
}
redirect2page('admin/site_options.php',$topass,$qs);

<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/site_options.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$active_module_code='core';
if (!empty($_GET['module_code'])) {
	$active_module_code=sanitize_and_format($_GET['module_code'],TYPE_STRING,$__field2format[FIELD_TEXTFIELD]);
}

$query="SELECT a.*,b.`module_name`,b.`module_type` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE b.`module_code`=a.`fk_module_code` AND a.`option_type`<>".OPTION_NA." ORDER BY b.`sort` ASC, a.`fk_module_code` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$site_options=array();
$i=-1;
$j=-1;
$last_module_code='';
while ($rsrow=mysql_fetch_assoc($res)) {
	if ($rsrow['fk_module_code']!=$last_module_code) {
		++$i;
		$site_options[$i]['module_name']='';
		switch ($rsrow['module_type']) {

			case MODULE_PAYMENT:
				$site_options[$i]['module_name']='Payment: ';
				break;

			case MODULE_FRAUD:
				$site_options[$i]['module_name']='Fraud Manager: ';
				break;

			case MODULE_WIDGET:
				$site_options[$i]['module_name']='Widget: ';
				break;

			case MODULE_SKIN:
				$site_options[$i]['module_name']='Skin: ';
				break;

		}
		$site_options[$i]['module_name'].=$rsrow['module_name'];
		$site_options[$i]['fk_module_code']=$rsrow['fk_module_code'];
		if ($active_module_code==$rsrow['fk_module_code']) {
			$site_options[$i]['class']='shown_options';
		} else {
			$site_options[$i]['class']='hidden_options';
		}
		$last_module_code=$rsrow['fk_module_code'];
		$j=0;
	} else {
		++$j;
	}
	$rsrow['config_value']=sanitize_and_format($rsrow['config_value'],TYPE_STRING,$__field2format[TEXT_DB2EDIT]);
	if ($rsrow['option_type']==FIELD_SELECT) {
		$rsrow['choices']=unserialize($rsrow['choices']);
	}
	switch ($rsrow['option_type']) {

		case FIELD_CHECKBOX:
			$rsrow['config_value']=($rsrow['config_value']==1) ? 'checked="checked"' : '';
			$rsrow['field']='<input class="input_chk" type="checkbox" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" value="1" '.$rsrow['config_value'].' />';
			break;

		case FIELD_TEXTFIELD:
			$rsrow['field']='<input class="input_tf" type="text" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" value="'.$rsrow['config_value'].'" />';
			break;

		case FIELD_INT:
			$rsrow['field']='<input class="number" type="text" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" value="'.$rsrow['config_value'].'" />';
			break;

		case FIELD_TEXTAREA:
			$rsrow['field']='<textarea class="input_ta" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" cols="" rows="">'.$rsrow['config_value'].'</textarea>';
			break;

		case FIELD_SELECT:
			$rsrow['field']='<select name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'">'.vector2options($rsrow['choices'],$rsrow['config_value']).'</select>';
			break;

		case FIELD_FILE:
			$rsrow['field']='<input class="text" type="file" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" /> <a class="remove_upl_file" href="processors/site_options_del_file.php?cid='.$rsrow['config_id'].'"><span>Remove</span></a> <p>'.$rsrow['config_value'].'</p>';
			break;

	}
	if (isset($rsrow['field'])) {
		$site_options[$i]['options'][]=$rsrow;
	}
}

//print_r($site_options);

$tpl->set_file('content','site_options.html');
$tpl->set_loop('site_options',$site_options);
$tpl->set_var('module_code',$active_module_code);
$tpl->process('content','content',TPL_MULTILOOP);

$tplvars['title']='Site Options';
$tplvars['css']='site_options.css';
$tplvars['page']='site_options';
include 'frame.php';

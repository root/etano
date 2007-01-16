<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/site_options.php
$Revision: 85 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/sessions.inc.php';
require_once '../includes/classes/phemplate.class.php';
require_once '../includes/vars.inc.php';
require_once '../includes/admin_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$active_module_code='core';
if (isset($_GET['module_code']) && !empty($_GET['module_code'])) {
	$active_module_code=sanitize_and_format($_GET['module_code'],TYPE_STRING,$__html2format[_HTML_TEXTFIELD_]);
}

$query="SELECT a.*,b.`module_name`,b.`module_type` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE b.`module_code`=a.`fk_module_code` AND a.`option_type`<>'".OPTION_NA."' ORDER BY b.`module_type` ASC, a.`fk_module_code` ASC";
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

			case _MODULE_PAYMENT_:
				$site_options[$i]['module_name']='Payment: ';
				break;

			case _MODULE_FRAUD_:
				$site_options[$i]['module_name']='Fraud Manager: ';
				break;

			case _MODULE_WIDGET_:
				$site_options[$i]['module_name']='Widget: ';
				break;

			case _MODULE_SKIN_:
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
	$rsrow['config_value']=sanitize_and_format($rsrow['config_value'],TYPE_STRING,$__html2format[TEXT_DB2EDIT]);
	switch ($rsrow['option_type']) {

		case OPTION_CHECKBOX:
			$rsrow['config_value']=($rsrow['config_value']==1) ? 'checked="checked"' : '';
			$rsrow['field']='<input class="input_chk" type="checkbox" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" value="1" '.$rsrow['config_value'].' />';
			break;

		case OPTION_TEXTFIELD:
			$rsrow['field']='<input class="input_tf" type="text" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" value="'.$rsrow['config_value'].'" />';
			break;

		case OPTION_TEXTAREA:
			$rsrow['field']='<textarea class="input_ta" name="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'" id="'.$rsrow['fk_module_code'].'_'.$rsrow['config_option'].'">'.$rsrow['config_value'].'</textarea>';
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
include 'frame.php';
?>
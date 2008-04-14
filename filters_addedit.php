<?php
/******************************************************************************
Etano
===============================================================================
File:                      	filters_addedit.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require 'includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/includes/tables/message_filters.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';
check_login_member('manage_folders');

$tpl=new phemplate(_BASEPATH_.'/skins_site/'.get_my_skin().'/','remove_nonjs');
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$output=$message_filters_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$tpl->set_var('addedit_filter',true);
	if (isset($_SESSION['topass']['input'])) {
		$output=$_SESSION['topass']['input'];
	}
} elseif (!empty($_GET['filter_id'])) {
	$filter_id=(int)$_GET['filter_id'];
	$query="SELECT * FROM `{$dbtable_prefix}message_filters` WHERE `filter_id`=$filter_id AND `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
}

switch ($output['filter_type']) {

	case FILTER_SENDER:
		$output['field_label']=$GLOBALS['_lang'][117];
		$output['field_value']='<input type="text" name="field_value" id="field_value" value="'.get_user_by_userid($output['field_value']).'" />';
		break;

	case FILTER_SENDER_PROFILE:
		foreach ($_pfields as $k=>$field) {
			if ($field['dbfield']==$output['field']) {
				$output['field_label']=sprintf($GLOBALS['_lang'][118],$field['label']);
				$output['field_value']='<select name="field_value" id="field_value">'.vector2options($field['accepted_values'],$output['field_value'],array(0)).'</select>';
				break;
			}
		}
		break;

	case FILTER_MESSAGE:
	default:
		break;

}

$my_folders=array(FOLDER_INBOX=>$GLOBALS['_lang'][110],FOLDER_OUTBOX=>$GLOBALS['_lang'][111],FOLDER_TRASH=>$GLOBALS['_lang'][112],FOLDER_SPAMBOX=>$GLOBALS['_lang'][113]);
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."' ORDER BY `folder` ASC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$my_folders[$rsrow[0]]=$rsrow[1];
}
$moveto_folders=$my_folders;
unset($moveto_folders[FOLDER_INBOX],$moveto_folders[FOLDER_OUTBOX],$moveto_folders[FOLDER_TRASH]);
$output['fk_folder_id']=vector2options($moveto_folders,$output['fk_folder_id']);
$my_folders=sanitize_and_format($my_folders,TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);

$tpl->set_file('content','filters_addedit.html');
$tpl->set_var('output',$output);
$tpl->set_var('o',$o);
$tpl->set_var('r',$r);
$tpl->process('content','content',TPL_OPTIONAL);

$tplvars['title']=$GLOBALS['_lang'][119];
$tplvars['page_title']=$GLOBALS['_lang'][119];
$tplvars['page']='filters_addedit';
$tplvars['css']='filters_addedit.css';
if (is_file('filters_addedit_left.php')) {
	include 'filters_addedit_left.php';
}
include 'frame.php';

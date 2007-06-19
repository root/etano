<?php
/******************************************************************************
newdsb
===============================================================================
File:                      	filters_addedit.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once 'includes/tables/message_filters.inc.php';
check_login_member('manage_folders');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=(isset($_GET['r']) && !empty($_GET['r'])) ? (int)$_GET['r'] : current($accepted_results_per_page);

$output=$message_filters_default['defaults'];
if (isset($_SESSION['topass']['input'])) {
	$tpl->set_var('addedit_filter',true);
	if (isset($_SESSION['topass']['input'])) {
		$output=$_SESSION['topass']['input'];
	}
} elseif (isset($_GET['filter_id']) && !empty($_GET['filter_id'])) {
	$filter_id=(int)$_GET['filter_id'];
	$query="SELECT * FROM `{$dbtable_prefix}message_filters` WHERE `filter_id`='$filter_id' AND `fk_user_id`='".$_SESSION['user']['user_id']."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
	}
}

switch ($output['filter_type']) {

	case FILTER_SENDER:
		$output['field_label']='user';	// translate this
		$output['field_value']='<input type="text" name="field_value" id="field_value" value="'.get_user_by_userid($output['field_value']).'" />';
		break;

	case FILTER_SENDER_PROFILE:
		foreach ($_pfields as $k=>$field) {
			if ($field['dbfield']==$output['field']) {
				$output['field_label']=sprintf('users with %s',$field['label']);	// translate this
				$output['field_value']='<select name="field_value" id="field_value">'.vector2options($field['accepted_values'],$output['field_value'],array(0)).'</select>';
				break;
			}
		}
		break;

	case FILTER_MESSAGE:
	default:
		break;

}

$my_folders=array(FOLDER_INBOX=>'INBOX',FOLDER_OUTBOX=>'SENT',FOLDER_TRASH=>'Trash',FOLDER_SPAMBOX=>'SPAMBOX'); // translate this
$query="SELECT `folder_id`,`folder` FROM `{$dbtable_prefix}user_folders` WHERE `fk_user_id`='".$_SESSION['user']['user_id']."' ORDER BY `folder` ASC";
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

$tplvars['title']='Add/Edit a filter';     // translate
$tplvars['page_title']='Add/Edit a filter';
$tplvars['page']='filters_addedit';
$tplvars['css']='filters_addedit.css';
if (is_file('filters_addedit_left.php')) {
	include 'filters_addedit_left.php';
}
include 'frame.php';
?>
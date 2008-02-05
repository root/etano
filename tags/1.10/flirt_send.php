<?php
/******************************************************************************
Etano
===============================================================================
File:                       flirt_send.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/mailbox.inc.php';

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
	$_GET['ft']=$output['ft'];
} elseif (!empty($_GET['to_id'])) {
	$output['fk_user_id']=(int)$_GET['to_id'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} else {
	trigger_error($GLOBALS['_lang'][120],E_USER_ERROR);
}

if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD] | FORMAT_RUENCODE,'');
}
$flirt_type=sanitize_and_format_gpc($_GET,'ft',TYPE_INT,0,0);
if ($flirt_type==FLIRT_INIT) {
	check_login_member('flirt_send');
} else {
	check_login_member('flirt_reply');
}

$flirts=array();
$query="SELECT `flirt_id`,`flirt_text` FROM `{$dbtable_prefix}flirts` WHERE `flirt_type`=$flirt_type";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$flirts[$rsrow[0]]=$rsrow[1];
}

$tpl->set_file('content','flirt_send.html');
$tpl->set_var('flirts',vector2radios($flirts,'flirt_id',0,array(),'class="flirts_list"'));
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']=$GLOBALS['_lang'][121];
$tplvars['page_title']=$GLOBALS['_lang'][121];
$tplvars['page']='flirt_send';
$tplvars['css']='flirt_send.css';
if (is_file('flirt_send_left.php')) {
	include 'flirt_send_left.php';
}
include 'frame.php';

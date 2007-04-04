<?php
/******************************************************************************
newdsb
===============================================================================
File:                       flirt_send.php
$Revision: 52 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/vars.inc.php';
require_once 'includes/user_functions.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
check_login_member(2);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
if (isset($_SESSION['topass']['input'])) {
	$output=$_SESSION['topass']['input'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} elseif (isset($_GET['to_id']) && !empty($_GET['to_id'])) {
	$output['fk_user_id']=(int)$_GET['to_id'];
	$output['_user_other']=get_user_by_userid($output['fk_user_id']);
} else {
	trigger_error('No receiver specified',E_USER_ERROR);     // translate
}

if (!isset($output['return']) && isset($_GET['return'])) {
	$output['return']=rawurlencode(sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__html2format[HTML_TEXTFIELD],''));
}
$flirt_type=sanitize_and_format_gpc($_GET,'ft',TYPE_INT,0,0);

$flirts=array();
$query="SELECT `flirt_id`,`flirt_text` FROM `{$dbtable_prefix}flirts` WHERE `flirt_type`='$flirt_type'";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_row($res)) {
	$flirts[$rsrow[0]]=$rsrow[1];
}

$tpl->set_file('content','flirt_send.html');
$tpl->set_var('flirts',vector2radios($flirts,'flirt_id',0,array(),'class="flirts_list"'));
$tpl->set_var('output',$output);
$tpl->process('content','content');

$tplvars['title']='Send a flirt';     // translate
$tplvars['page_title']='Send a flirt';
$tplvars['page']='flirt_send';
$tplvars['css']='flirt_send.css';
if (is_file('flirt_send_left.php')) {
	include 'flirt_send_left.php';
}
include 'frame.php';
?>
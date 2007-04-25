<?php
/******************************************************************************
newdsb
===============================================================================
File:                       my_searches.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once 'includes/sessions.inc.php';
require_once 'includes/vars.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once 'includes/classes/phemplate.class.php';
require_once 'includes/user_functions.inc.php';
check_login_member(11);

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$from="`{$dbtable_prefix}user_searches`";
$where="`fk_user_id`='".$_SESSION['user']['user_id']."'";

$loop=array();
$query="SELECT `search_id`,`title`,`is_default`,`alert` FROM $from WHERE $where ORDER BY `search_id` DESC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['encoded_title']=sanitize_and_format($rsrow['title'],TYPE_STRING,FORMAT_RUENCODE);
	$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['is_default']=(!empty($rsrow['is_default'])) ? 'checked="checked"' : '';
	$rsrow['alert']=(!empty($rsrow['alert'])) ? 'checked="checked"' : '';
	$loop[]=$rsrow;
}

$output['return']='my_searches.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return']=rawurlencode($output['return']);
$tpl->set_file('content','my_searches.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='My Searches';
$tplvars['page_title']='My Searches';
$tplvars['page']='my_searches';
$tplvars['css']='my_searches.css';
if (is_file('my_searches_left.php')) {
	include 'my_searches_left.php';
}
include 'frame.php';
?>
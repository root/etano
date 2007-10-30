<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_searches.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once 'includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once 'includes/user_functions.inc.php';
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/my_searches.inc.php';
check_login_member('save_searches');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');
$output=array();

$from="`{$dbtable_prefix}user_searches`";
$where="`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";

$loop=array();
$query="SELECT `search_id`,`title`,`is_default`,`alert`,`search_qs` FROM $from WHERE $where ORDER BY `search_id` DESC";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
while ($rsrow=mysql_fetch_assoc($res)) {
	$rsrow['encoded_title']=sanitize_and_format($rsrow['title'],TYPE_STRING,FORMAT_RUENCODE);
	$rsrow['title']=sanitize_and_format($rsrow['title'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
	$rsrow['is_default']=(!empty($rsrow['is_default'])) ? 'checked="checked"' : '';
	$rsrow['alert']=(!empty($rsrow['alert'])) ? 'checked="checked"' : '';
	$loop[]=$rsrow;
}

$output['return2me']='my_searches.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_searches.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']=$GLOBALS['_lang'][242];
$tplvars['page_title']=$GLOBALS['_lang'][242];
$tplvars['page']='my_searches';
$tplvars['css']='my_searches.css';
if (is_file('my_searches_left.php')) {
	include 'my_searches_left.php';
}
include 'frame.php';

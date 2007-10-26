<?php
/******************************************************************************
Etano
===============================================================================
File:                       my_responses.php
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
require_once 'includes/tables/user_mtpls.inc.php';
check_login_member('saved_messages');

$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=!empty($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);

$config['bbcode_message']=get_site_option('bbcode_message','core');

$from="`{$dbtable_prefix}user_mtpls`";
$where="`fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['user_id']."'";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>=$totalrows) {
		$o=$totalrows-$r;
		$o=$o>=0 ? $o : 0;
	}
	$query="SELECT `mtpl_id`,`subject`,`message_body` FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['subject']=sanitize_and_format($rsrow['subject'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['message_body']=sanitize_and_format($rsrow['message_body'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		if ($config['bbcode_message']) {
			$rsrow['message_body']=bbcode2html($rsrow['message_body']);
		}
		$loop[]=$rsrow;
	}

	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='my_responses.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','my_responses.html');
$tpl->set_var('output',$output);
$tpl->set_loop('loop',$loop);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
unset($loop);

$tplvars['title']='Manage your saved responses';     // translate
$tplvars['page_title']='Saved Responses';
$tplvars['page']='my_responses';
$tplvars['css']='my_responses.css';
if (is_file('my_responses_left.php')) {
	include 'my_responses_left.php';
}
include 'frame.php';

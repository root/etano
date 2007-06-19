<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/site_bans.php
$Revision: 21 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOSTNAME_,_DBUSERNAME_,_DBPASSWORD_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
require_once '../includes/logs.inc.php';
allow_dept(DEPT_MODERATOR | DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

$o=isset($_GET['o']) ? (int)$_GET['o'] : 0;
$r=isset($_GET['r']) ? (int)$_GET['r'] : current($accepted_results_per_page);
$where="a.`fk_lk_id_reason`=b.`fk_lk_id` AND b.`skin`='".get_default_skin_code()."'";
$from="`{$dbtable_prefix}site_bans` a,`{$dbtable_prefix}lang_strings` b";

$query="SELECT count(*) FROM $from WHERE $where";
if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
$totalrows=mysql_result($res,0,0);

$loop=array();
if (!empty($totalrows)) {
	if ($o>$totalrows) {
		$o=$totalrows-$r;
	}
	$config=get_site_option(array('datetime_format'),'def_user_prefs');
	$query="SELECT a.`ban_id`,a.`ban_type`,a.`what`,b.`lang_value` as `reason`,UNIX_TIMESTAMP(a.`since`) as `since` FROM $from WHERE $where LIMIT $o,$r";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if ($rsrow['ban_type']==_PUNISH_BANIP_) {
			$rsrow['what']=long2ip($rsrow['what']);
		}
		$rsrow['ban_type']=$accepted_punishments[$rsrow['ban_type']];
		$rsrow['reason']=sanitize_and_format($rsrow['reason'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['since']=strftime($config['datetime_format'],$rsrow['since']);
		$loop[]=$rsrow;
	}
	$output['pager2']=pager($totalrows,$o,$r);
}

$output['return2me']='site_bans.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
}
$output['return2me']=rawurlencode($output['return2me']);
$tpl->set_file('content','site_bans.html');
$tpl->set_loop('loop',$loop);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_NOLOOP);
$tpl->drop_loop('loop');
$tpl->drop_var('output.pager2');
unset($loop);

$tplvars['title']='Banned Members Management';
$tplvars['page']='site_bans';
include 'frame.php';
?>
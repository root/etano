<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/user_products.php
$Revision: 323 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$tpl=new phemplate('skin/','remove_nonjs');

if (!empty($_GET['uid'])) {
	$output['uid']=(int)$_GET['uid'];
	$output['return']=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
	$config=get_site_option(array('date_format','time_offset'),'def_user_prefs');

	$query="SELECT `site_id`,`baseurl`,`license` FROM `user_sites` WHERE `fk_user_id`=".$output['uid'];
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$sites=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$sites[$rsrow['site_id']]=$rsrow;
	}

	$query="SELECT a.`uprod_id`,b.`prod_name`,a.`fk_site_id`,c.`gateway`,c.`gw_txn`,UNIX_TIMESTAMP(c.`date`) as `date_purchased`,a.`license` FROM `user_products` a,`products` b,`{$dbtable_prefix}payments` c WHERE a.`fk_prod_id`=b.`prod_id` AND a.`fk_payment_id`=c.`payment_id` AND a.`fk_user_id`=".$output['uid']." ORDER BY a.`fk_site_id`,a.`uprod_id`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow['prod_name']=sanitize_and_format($rsrow['prod_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
		$rsrow['date_purchased']=strftime($config['date_format'],$rsrow['date_purchased']+$config['time_offset']);
		$sites[$rsrow['fk_site_id']]['loop2'][]=$rsrow;
	}

	$loop=array();
	foreach ($sites as $sid=>$v) {
		$loop[]=$v;
	}
	$output['return2me']='user_products.php';
	if (!empty($_SERVER['QUERY_STRING'])) {
		$output['return2me'].='?'.$_SERVER['QUERY_STRING'];
	}
	$output['return2me']=rawurlencode($output['return2me']);
	$tpl->set_file('content','user_products.html');
	$tpl->set_loop('loop',$loop);
	$tpl->set_var('output',$output);
	$tpl->set_var('tplvars',$tplvars);
	print $tpl->process('content','content',TPL_MULTILOOP,TPL_FINISH);
}

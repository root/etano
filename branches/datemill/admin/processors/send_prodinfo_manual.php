<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/processors/send_prodinfo_manual.php
$Revision: 217 $
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require_once '../../includes/common.inc.php';
db_connect(_DBHOST_,_DBUSER_,_DBPASS_,_DBNAME_);
require_once '../../includes/admin_functions.inc.php';
allow_dept(DEPT_ADMIN);

$qs='';
$qs_sep='';
$topass=array();
$uprod_id=isset($_GET['uprod_id']) ? (int)$_GET['uprod_id'] : 0;
// no need to urldecode because of the GET
$return=sanitize_and_format_gpc($_GET,'return',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');

if (!empty($uprod_id)) {
	$query="SELECT a.`fk_prod_id` as `prod_id`,a.`license`,a.`license_md5`,a.`fk_payment_id`,a.`fk_user_id`,a.`fk_site_id`,b.`user`,b.`email`,c.`f1` as `name` FROM `user_products` a,`{$dbtable_prefix}user_accounts` b,`{$dbtable_prefix}user_profiles` c WHERE a.`fk_user_id`=b.`user_id` AND a.`fk_user_id`=c.`fk_user_id` AND a.`uprod_id`=$uprod_id";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$output=mysql_fetch_assoc($res);
		$query="UPDATE `{$dbtable_prefix}payments` SET `is_suspect`=0,`suspect_reason`='' WHERE `payment_id`=".$output['fk_payment_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$output['pass']=gen_pass(6);
		$query="UPDATE `{$dbtable_prefix}user_accounts` SET `pass`=md5('".$output['pass']."') WHERE `user_id`=".$output['fk_user_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$query="UPDATE `user_sites` SET `active`=1 WHERE `site_id`=".$output['fk_site_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$tpl=new phemplate(_BASEPATH_.'/admin/skin/','remove_nonjs');
		if ($_GET['t']=='new') {
			$tpl->set_file('gateway_text','thankyou_prod_ok_manual.html');
			$subject=sprintf('Your %s purchase details',_SITENAME_);
		} elseif ($_GET['t']=='up') {
			$tpl->set_file('gateway_text','download_upgrade_email.html');
			$subject='Your DSB to Etano upgrade details';
			$output['start_date']=date('F jS, Y');
			$output['end_date']=date('F jS, Y',mktime(0,0,0,date('m')+2,date('d')-1,date('Y')));
		}
		$tpl->set_var('output',$output);
		$tpl->set_var('tplvars',$GLOBALS['tplvars']);	// need this for the email below
		$tpl->process('gateway_text','gateway_text',TPL_OPTIONAL);
		$tpl->drop_var('output');

		send_template_email($output['email'],$subject,'general.html','def',array('content'=>$tpl->get_var_silent('gateway_text')));
		$topass['message']['type']=MESSAGE_INFO;
		$topass['message']['text']='Ok';
	}
}

if (!empty($return)) {
	$nextpage=_BASEURL_.'/admin/'.$return;
} else {
	$nextpage=_BASEURL_.'/admin/user_products.php?uprod_id='.$uprod_id;
}
redirect2page($nextpage,$topass,'',true);

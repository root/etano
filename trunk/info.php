<?php
/******************************************************************************
Etano
===============================================================================
File:                       info.php
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
require_once _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/info.php';
global $tplvars;

$type=isset($_GET['type']) ? $_GET['type'] : '';
$tpl=new phemplate($tplvars['tplrelpath'].'/','remove_nonjs');

$output=array();
switch ($type) {
	case 'signup':
		$template='info_signup.html';
		$output['email']=isset($_SESSION[_LICENSE_KEY_]['user']['email']) ? $_SESSION[_LICENSE_KEY_]['user']['email'] : (isset($_GET['email']) ? $_GET['email'] : '');
		$tplvars['page_title']=$GLOBALS['_lang'][228];
		$tplvars['page']='info_signup';
		break;

	case 'upgrade':
		$template='info_upgrade.html';
		$tplvars['page_title']=$GLOBALS['_lang'][229];
		$tplvars['page']='info_upgrade';
		break;

	case 'mailsent':
		$template='info_mailsent.html';
		$tplvars['page_title']=$GLOBALS['_lang'][230];
		$tplvars['page']='info_mailsent';
		break;

	case 'acctactiv':	// activate account
		$template='info_acctactiv.html';
		$tplvars['page_title']=$GLOBALS['_lang'][231];
		$tplvars['page']='info_acctactiv';
		$output['uid']=sanitize_and_format_gpc($_GET,'uid',TYPE_INT,0,0);
		$output['email']=sanitize_and_format_gpc($_GET,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		break;

	case 'acctok':	// account confirmed
		$template='info_acctok.html';
		$tplvars['page_title']=$GLOBALS['_lang'][232];
		$tplvars['page']='info_acctok';
		break;

	case 'profile_na':	// profile is not approved yet
		$template='info_profilena.html';
		$tplvars['page_title']=$GLOBALS['_lang'][233];
		$tplvars['page']='info_profilena';
		break;

	case 'access':	// no access to the requested page, show the upgrade options.
		check_login_member('auth');	// make sure they logged in
		$template='info_access.html';
		$tplvars['page_title']=$GLOBALS['_lang'][234];
		$tplvars['page']='info_access';

		$query="SELECT a.`module_code`,a.`module_name`,a.`module_diz` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_PAYMENT." AND b.`fk_module_code`=a.`module_code` AND b.`config_option`='enabled' AND `config_value`=1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$active_gateways=array();
		while ($rsrow=mysql_fetch_assoc($res)) {
			if (is_file(_BASEPATH_.'/plugins/payment/'.$rsrow['module_code'].'/'.$rsrow['module_code'].'.class.php')) {
				include_once _BASEPATH_.'/plugins/payment/'.$rsrow['module_code'].'/'.$rsrow['module_code'].'.class.php';
				$temp='payment_'.$rsrow['module_code'];
				$rsrow['call']=new $temp();
				$rsrow['module_name']=sanitize_and_format($rsrow['module_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				$rsrow['module_diz']=sanitize_and_format($rsrow['module_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
				$active_gateways[]=$rsrow;
			}
		}
		$query="SELECT `subscr_id`,`subscr_name`,`subscr_diz`,`price`,`currency`,`is_recurent`,`duration` FROM `{$dbtable_prefix}subscriptions` WHERE `is_visible`=1";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$loop=array();
		$payment=array();
		$payment['user_id']=$_SESSION[_LICENSE_KEY_]['user']['user_id'];
		$payment['dm_item_type']='subscr';
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['subscr_name']=sanitize_and_format($rsrow['subscr_name'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			$rsrow['subscr_diz']=sanitize_and_format($rsrow['subscr_diz'],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
			if ((int)$rsrow['price']==(float)$rsrow['price']) {
				$rsrow['price']=number_format($rsrow['price'],0);
			} else {
				$rsrow['price']=number_format($rsrow['price'],2);
			}
			$payment['internal_name']=$rsrow['subscr_name'];
			$payment['internal_id']=$rsrow['subscr_id'];
			$payment['price']=$rsrow['price'];
			$payment['is_recurent']=$rsrow['is_recurent'];
			$payment['duration']=$rsrow['duration'];
			$payment['currency']=$rsrow['currency'];
			for ($i=0;isset($active_gateways[$i]);++$i) {
				$rsrow['payment_links'][]=$active_gateways[$i]['call']->get_buy_button($payment);
			}
			$rsrow['payment_links']=join('</li><li>',$rsrow['payment_links']);
			$loop[]=$rsrow;
		}
		$tpl->set_loop('loop',$loop);
		break;

	default:
		$template='info.html';
		$tplvars['page_title']=$GLOBALS['_lang'][235];
		$tplvars['page']='info';

}
$tpl->set_file('content',$template);
$tpl->set_var('output',$output);
$tpl->process('content','content',TPL_LOOP | TPL_OPTIONAL);

$tplvars['title']=$GLOBALS['_lang'][227];
$tplvars['css']='info.css';
if (is_file('info_left.php')) {
	include 'info_left.php';
}
$no_timeout=true;
include 'frame.php';

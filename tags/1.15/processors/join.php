<?php
/******************************************************************************
Etano
===============================================================================
File:                       processors/join.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

require '../includes/common.inc.php';
require _BASEPATH_.'/includes/user_functions.inc.php';
require _BASEPATH_.'/skins_site/'.get_my_skin().'/lang/join.inc.php';

if (is_file(_BASEPATH_.'/events/processors/join.php')) {
	include _BASEPATH_.'/events/processors/join.php';
}

$error=false;
$topass=array();
$nextpage='info.php';
$qs='';
$qs_sep='';
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$input=array();
// get the input we need and sanitize it
	$input['page']=sanitize_and_format_gpc($_POST,'page',TYPE_INT,0,1);
	if ($input['page']==1) {
		$input['user']=strtolower(sanitize_and_format_gpc($_POST,'user',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
		$input['pass']=sanitize_and_format_gpc($_POST,'pass',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
		$input['email']=strtolower(sanitize_and_format_gpc($_POST,'email',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
		$input['email2']=strtolower(sanitize_and_format_gpc($_POST,'email2',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],''));
		$input['agree']=sanitize_and_format_gpc($_POST,'agree',TYPE_INT,0,0);

		if (!preg_match('/^[a-z0-9_]+$/',$input['user']) || strlen($input['user'])<4 || strlen($input['user'])>20) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][63];
			$input['error_user']='red_border';
		}
		if (!$error && ($input['user']=='guest' || get_userid_by_user($input['user']))) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][64];
			$input['error_user']='red_border';
		}
		if (!$error && empty($input['pass'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][65];
			$input['error_pass']='red_border';
		}
		if (!$error && $input['email']!=$input['email2']) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][37];
			$input['error_email']='red_border';
		}
		if (!$error && !preg_match('/^[a-z0-9\-\._]+@[a-z0-9\-]+(\.[a-z0-9\-]+)+$/',$input['email'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][66];
			$input['error_email']='red_border';
		}
		if (!$error) {
			$query="SELECT `".USER_ACCOUNT_ID."` FROM `".USER_ACCOUNTS_TABLE."` WHERE `email`='".$input['email']."' LIMIT 1";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			if (mysql_num_rows($res)) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=sprintf($GLOBALS['_lang'][67],$input['email']);
				$input['error_email']='red_border';
			}
		}
		if (get_site_option('use_captcha','core')) {
			$captcha=sanitize_and_format_gpc($_POST,'captcha',TYPE_STRING,$__field2format[FIELD_TEXTFIELD],'');
			if (!$error && (!isset($_SESSION['captcha_word']) || strcasecmp($captcha,$_SESSION['captcha_word'])!=0)) {
				$error=true;
				$topass['message']['type']=MESSAGE_ERROR;
				$topass['message']['text'][]=$GLOBALS['_lang'][24];
				$input['error_captcha']='red_border';
			}
		}
		unset($_SESSION['captcha_word']);
		if (!$error && empty($input['agree'])) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			$topass['message']['text'][]=$GLOBALS['_lang'][68];
			$input['error_agree']='red_border';
		}
	}

	$next_join_pages=array();
	$my_fields=array();
	$dbfield2pfieldid=array();
	$on_changes=array();
	foreach ($_pfields as $field_id=>$field) {
		// what fields should be processed on this page?
		if (isset($field->config['reg_page'])) {
			if ($field->config['reg_page']==$input['page']) {
				$my_fields[]=$field_id;
				$dbfield2pfieldid[$field->config['dbfield']]=$field_id;
			}
			// what pages are after us?
			if ($field->config['reg_page']>$input['page']) {
				$next_join_pages[$field->config['reg_page']]=1;
			}
		}
	}

	for ($i=0;isset($my_fields[$i]);++$i) {
		$field=&$_pfields[$my_fields[$i]];
		$field->set_value($_POST,true);
		if (true!==($temp=$field->validation_server())) {
			$error=true;
			$topass['message']['type']=MESSAGE_ERROR;
			if (empty($temp['text'])) {
				$topass['message']['text']=$GLOBALS['_lang'][69];
			} else {
				$topass['message']['text']=$temp['text'];
			}
			$input['error_'.$field->config['dbfield']]='red_border';
		}
		if (!$error) {
			if (!empty($field->config['fn_on_change'])) {
				$on_changes[]=array('fn'=>$field->config['fn_on_change'],
									'param2'=>$field->get_value(),
									'param3'=>$field->config['dbfield']);
			}
		}
	}

	if (!$error) {
		if ($input['page']==1) {
			$input['temp_pass']=md5(gen_pass(6));
			$query="INSERT IGNORE INTO `".USER_ACCOUNTS_TABLE."` SET `".USER_ACCOUNT_USER."`='".$input['user']."',`".USER_ACCOUNT_PASS."`=md5('".$input['pass']."'),`email`='".$input['email']."',`membership`=2,`status`=".ASTAT_UNVERIFIED.",`temp_pass`='".$input['temp_pass']."'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			$_SESSION[_LICENSE_KEY_]['user']['reg_id']=mysql_insert_id();
			$_SESSION[_LICENSE_KEY_]['user']['user']=$input['user'];	// for `dsb_payments`
			$_SESSION[_LICENSE_KEY_]['user']['email']=$input['email'];	// for info_signup.html
			$input['uid']=$_SESSION[_LICENSE_KEY_]['user']['reg_id'];
			send_template_email($input['email'],sprintf($GLOBALS['_lang'][70],_SITENAME_),'confirm_reg.html',get_my_skin(),$input);
		}
		$query="SELECT `fk_user_id` FROM `{$dbtable_prefix}user_profiles` WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['reg_id'];
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$is_update=false;
		if (mysql_num_rows($res)) {
			$is_update=true;
		}
		$now=gmdate('YmdHis');
		if ($is_update) {
			$query="UPDATE `{$dbtable_prefix}user_profiles` SET `last_changed`='$now'";
		} else {
			$query="INSERT INTO `{$dbtable_prefix}user_profiles` SET `fk_user_id`='".$_SESSION[_LICENSE_KEY_]['user']['reg_id']."',`last_changed`='$now',`date_added`='$now',`score`='".add_member_score(0,'join',1,true)."'";
		}
		if ($input['page']==1) {
			$query.=",`_user`='".$input['user']."'";
			if (get_site_option('manual_profile_approval','core')==1) {
				$query.=",`status`=".STAT_PENDING;
			} else {
				$query.=",`status`=".STAT_APPROVED;
			}
		}
		for ($i=0;isset($my_fields[$i]);++$i) {
			$query.=','.$_pfields[$my_fields[$i]]->query_set();
		}
		if ($is_update) {
			$query.=" WHERE `fk_user_id`=".$_SESSION[_LICENSE_KEY_]['user']['reg_id'];
		}
		if (isset($_on_before_insert)) {
			for ($i=0;isset($_on_before_insert[$i]);++$i) {
				call_user_func($_on_before_insert[$i]);
			}
		}
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		// execute the on_change triggers of every field
		for ($i=0;isset($on_changes[$i]);++$i) {
			if (function_exists($on_changes[$i]['fn'])) {
				eval($on_changes[$i]['fn'].'($_SESSION[\'user\'][\'reg_id\'],$on_changes[$i][\'param2\'],$on_changes[$i][\'param3\']);');
			}
		}
		// auto subscriptions
		if (!isset($_GET['nas'])) {
			$query="SELECT a.`dbfield`,a.`field_value`,b.`subscr_id`,b.`is_recurent`,b.`m_value_to`,b.`duration` FROM `{$dbtable_prefix}subscriptions_auto` a, `{$dbtable_prefix}subscriptions` b WHERE a.`dbfield` IN ('','".join("','",array_keys($input))."') AND a.`fk_subscr_id`=b.`subscr_id` AND a.`date_start`='0000-00-00'";
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
			while ($rsrow=mysql_fetch_assoc($res)) {
				if ((!empty($rsrow['dbfield']) && $_pfields[$dbfield2pfieldid[$rsrow['dbfield']]]->get_value()==$rsrow['field_value']) || empty($rsrow['dbfield'])) {
					$qs.=$qs_sep.'nas=1';	// no more auto_subscr checking from now on
					$qs_sep='&';
					$query="UPDATE `".USER_ACCOUNTS_TABLE."` SET `membership`=".$rsrow['m_value_to']." WHERE `".USER_ACCOUNT_ID."`=".$_SESSION[_LICENSE_KEY_]['user']['reg_id'];
					if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					// save as a payment with amount 0
					$query="INSERT INTO `{$dbtable_prefix}payments` (`is_active`,`fk_user_id`,`_user`,`is_subscr`,`fk_subscr_id`,`is_recurring`,`email`,`m_value_to`,`paid_from`,`paid_until`,`date`) VALUES (1,'".$_SESSION[_LICENSE_KEY_]['user']['reg_id']."','".$_SESSION[_LICENSE_KEY_]['user']['user']."',1,'".$rsrow['subscr_id']."','".$rsrow['is_recurent']."','".$_SESSION[_LICENSE_KEY_]['user']['email']."','".$rsrow['m_value_to']."','$now','$now'+INTERVAL ".$rsrow['duration'].' DAY,now())';
					if (!($res2=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					break;
				}
			}
		}

		ksort($next_join_pages,SORT_NUMERIC);
		if (!empty($next_join_pages)) {
			$temp=array_keys($next_join_pages);
			$page=array_shift($temp);
			$nextpage='join.php';
			$qs.=$qs_sep.'p='.$page;
			$qs_sep='&';
		} else {
			unset($_SESSION[_LICENSE_KEY_]['user']['auto_subscr']);
			$nextpage='info.php';
			$qs.=$qs_sep.'type=signup';
			$qs_sep='&';
		}
		if (isset($_on_after_insert)) {
			for ($i=0;isset($_on_after_insert[$i]);++$i) {
				call_user_func($_on_after_insert[$i]);
			}
		}
	} else {
		$nextpage='join.php';
		$topass['input']=$_POST;
		if (isset($_on_error)) {
			for ($i=0;isset($_on_error[$i]);++$i) {
				call_user_func($_on_error[$i]);
			}
		}
	}
}
redirect2page($nextpage,$topass,$qs);

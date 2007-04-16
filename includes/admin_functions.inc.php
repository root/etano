<?php
/******************************************************************************
newdsb
===============================================================================
File:                       admin/includes/admin_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://forum.datemill.com
*******************************************************************************
* See the "softwarelicense.txt" file for license.                             *
******************************************************************************/

//if (function_exists('admin_error')) {
//	set_error_handler('admin_error');
//} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
//}

require_once 'general_functions.inc.php';
$_lang=array();
$def_skin=isset($_SESSION['admin']['def_skin']) ? $_SESSION['admin']['def_skin'] : get_default_skin_dir();
require_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/strings.inc.php';
$_pfields=array();
$_pcats=array();
require_once 'fields.inc.php';

define('DEPT_MODERATOR',2);
define('DEPT_ADMIN',4);

define('OPTION_NA',0);

define('AMTPL_REJECT_MEMBER',1);
define('AMTPL_REJECT_PHOTO',2);
define('AMTPL_REJECT_BLOG',3);

// language key types
define('LK_SITE',0);
define('LK_FIELD',1);
define('LK_MESSAGE',2);

$accepted_htmltype=array(HTML_SELECT=>'Select box',HTML_CHECKBOX_LARGE=>'Multi checks',HTML_TEXTFIELD=>'Textfield',HTML_TEXTAREA=>'Textarea',HTML_DATE=>'Date',HTML_LOCATION=>'Location',HTML_RANGE=>'Range');
$field_dbtypes=array(HTML_TEXTFIELD=>"varchar(100) not null default ''",HTML_SELECT=>'int(5) not null default 0',HTML_FK_SELECT=>'int(10) not null default 0',HTML_TEXTAREA=>"text not null default ''",HTML_CHECKBOX_LARGE=>"text not null default ''",HTML_FILE=>"varchar(64) not null default ''",HTML_DATE=>'date not null',HTML_INT=>'int(5) not null default 0',HTML_FLOAT=>'double not null default 0');
$accepted_admin_depts=array(DEPT_ADMIN=>'Administrator',DEPT_MODERATOR=>'Moderator');
$accepted_astats=array(ASTAT_SUSPENDED=>'Suspended',ASTAT_UNVERIFIED=>'Unactivated',ASTAT_ACTIVE=>'Active');
$accepted_pstats=array(STAT_PENDING=>'Awaiting approval',STAT_EDIT=>'Requires edit',STAT_APPROVED=>'Approved');
$accepted_yesno=array(0=>'No',1=>'Yes');
$country_prefered_input=array('s'=>'state/city selection','z'=>'zip/postal code');

// you shouldn't call this function directly. Instead set this to set_error_handler() and use the trigger_error method
function admin_error($errlevel,$text,$file='unset',$line='unset') {
	$fatal_errors=array(E_USER_ERROR,E_ERROR,E_COMPILE_ERROR);
	if (in_array($errlevel,$fatal_errors) || (defined('_DEBUG_') && _DEBUG_!=0)) {
		require_once _BASEPATH_.'/includes/classes/phemplate.class.php';
		$tpl=new phemplate(_BASEPATH_.'/admin/skin/','remove_nonjs');
		$text.="<br/>";
		if (_DEBUG_==1) {
			$text.="<br/>Line: $line<br/>File: $file";
		} elseif (_DEBUG_==2) {
			ob_start();
			print_r(debug_backtrace());
			$text.=nl2br(str_replace(' ','&nbsp;',ob_get_contents()));
			ob_end_clean();
		}
		$tpl->set_file('content','error.html');
		$tpl->set_var('my_message',$text);
		$content=$tpl->process('','content',TPL_FINISH);

		$title='Error!';
		include _BASEPATH_.'/admin/frame.php';
		exit;
	}
}


function allow_dept($levels=DEPT_ADMIN) {
	$myreturn=false;
	if (isset($_SESSION['admin']['admin_id'])) {
		if (((int)$levels) & ((int)$_SESSION['admin']['dept_id'])) {
			$myreturn=true;
		} else {
			$topass['message']='You are not authorized to use this section.';
			redirect2page('admin/cpanel.php',$topass);
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please login first.';
		redirect2page('admin/index.php',$topass);
	}
	return $myreturn;
}


function regenerate_acclevels_array() {
	require_once _BASEPATH_.'/includes/classes/modman.class.php';
	global $dbtable_prefix;
	$query="SELECT `level_id`,`level` FROM `{$dbtable_prefix}access_levels`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$towrite="<?php\n\$_access_level=array(-1=>65534,0=>65535";
	while ($rsrow=mysql_fetch_row($res)) {
		$towrite.=','.$rsrow[0].'=>'.$rsrow[1];
	}
	$towrite.=");\n";
	$modman=new modman();
	$modman->fileop->file_put_contents(_BASEPATH_.'/includes/access_levels.inc.php',$towrite);
}


function regenerate_fields_array() {
	require_once _BASEPATH_.'/includes/classes/modman.class.php';
	global $dbtable_prefix;
	$query="SELECT * FROM `{$dbtable_prefix}profile_fields` ORDER BY `order_num` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$towrite="<?php\n";
	$profile_categs=array();
	$basic_search_fields=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2EDIT]);
		$id=$rsrow['order_num'];
		if (!empty($rsrow['for_basic']) && !empty($rsrow['searchable'])) {
			$basic_search_fields[]=$id;
		}
		$profile_categs[$rsrow['fk_pcat_id']][]=$rsrow['order_num'];
		$towrite.="\$_pfields[$id]['label']=\$_lang[".$rsrow['fk_lk_id_label']."];\n";
		$towrite.="\$_pfields[$id]['html_type']=".$rsrow['html_type'].";\n";
		if (!empty($rsrow['searchable'])) {
			$towrite.="\$_pfields[$id]['searchable']=true;\n";
			$towrite.="\$_pfields[$id]['search_type']=".$rsrow['search_type'].";\n";
			$towrite.="\$_pfields[$id]['search_label']=\$_lang[".$rsrow['fk_lk_id_search']."];\n";
		}
		if (!empty($rsrow['at_registration'])) {
			$towrite.="\$_pfields[$id]['reg_page']=".$rsrow['reg_page'].";\n";
		}
		if (!empty($rsrow['required'])) {
			$towrite.="\$_pfields[$id]['required']=true;\n";
		}
		$towrite.="\$_pfields[$id]['editable']=".(!empty($rsrow['editable']) ? 'true' : 'false').";\n";
		$towrite.="\$_pfields[$id]['visible']=".(!empty($rsrow['visible']) ? 'true' : 'false').";\n";
		$towrite.="\$_pfields[$id]['dbfield']='".$rsrow['dbfield']."';\n";
		$towrite.="\$_pfields[$id]['fk_pcat_id']=".$rsrow['fk_pcat_id'].";\n";
		if (!empty($rsrow['fn_on_change'])) {
			$towrite.="\$_pfields[$id]['fn_on_change']='".$rsrow['fn_on_change']."';\n";
		}

		switch ($rsrow['html_type']) {

			case HTML_SELECT:
			case HTML_CHECKBOX_LARGE:
				if (!empty($rsrow['accepted_values']) && $rsrow['accepted_values']!='||') {
					$towrite.="\$_pfields[$id]['accepted_values']=array('-',\$_lang[".str_replace('|',"],\$_lang[",substr($rsrow['accepted_values'],1,-1))."]);\n";
				} else {
					$towrite.="\$_pfields[$id]['accepted_values']=array('-');\n";
				}
				if (!empty($rsrow['default_value']) && $rsrow['default_value']!='||') {
					$rsrow['default_value']=explode('|',substr($rsrow['default_value'],1,-1));
					// for all fields whose default_values are indexes in accepted_values we increment them with 1 because we
					// add the default value "-" as the first element in every accepted_values array.
					for ($i=0;isset($rsrow['default_value'][$i]);++$i) {
						++$rsrow['default_value'][$i];
					}
					$towrite.="\$_pfields[$id]['default_value']=array(".join(",",$rsrow['default_value']).");\n";
				} else {
					$towrite.="\$_pfields[$id]['default_value']=array();\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					// for all fields whose default_searches are indexes in accepted_values we increment them with 1 because we
					// add the default value "-" as the first element in every accepted_values array.
					for ($i=0;isset($rsrow['default_search'][$i]);++$i) {
						++$rsrow['default_search'][$i];
					}
					$towrite.="\$_pfields[$id]['default_search']=array(".join(",",$rsrow['default_search']).");\n";
				} else {
					$towrite.="\$_pfields[$id]['default_search']=array();\n";
				}
				break;

			case HTML_DATE:
				if (!empty($rsrow['accepted_values']) && $rsrow['accepted_values']!='||') {
					$towrite.="\$_pfields[$id]['accepted_values']=array('-','".str_replace('|',"','",substr($rsrow['accepted_values'],1,-1))."');\n";
				} else {
					$towrite.="\$_pfields[$id]['accepted_values']=array('-');\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					$towrite.="\$_pfields[$id]['default_search']=array(".join(",",$rsrow['default_search']).");\n";
				} else {
					$towrite.="\$_pfields[$id]['default_search']=array();\n";
				}
				break;

			case HTML_LOCATION:
				if (!empty($rsrow['default_value']) && $rsrow['default_value']!='||') {
					$rsrow['default_value']=explode('|',substr($rsrow['default_value'],1,-1));
					$towrite.="\$_pfields[$id]['default_value']=array('".join("','",$rsrow['default_value'])."');\n";
				} else {
					$towrite.="\$_pfields[$id]['default_value']=array();\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					$towrite.="\$_pfields[$id]['default_search']=array('".join("','",$rsrow['default_search'])."');\n";
				} else {
					$towrite.="\$_pfields[$id]['default_search']=array();\n";
				}
				break;

			case HTML_TEXTFIELD:
			case HTML_TEXTAREA:
				break;

		}

		$towrite.="\$_pfields[$id]['help_text']=\$_lang[".$rsrow['fk_lk_id_help']."];\n";
		$towrite.="\n";
	}
	$towrite.="\n";
	// profile categories now
	$query="SELECT * FROM `{$dbtable_prefix}profile_categories`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($profile_categs[$rsrow['pcat_id']])) {
			$towrite.='$_pcats['.$rsrow['pcat_id']."]['pcat_name']=\$_lang[".$rsrow['fk_lk_id_pcat']."];\n";
			$towrite.='$_pcats['.$rsrow['pcat_id']."]['access_level']=".$rsrow['access_level'].";\n";
			$towrite.='$_pcats['.$rsrow['pcat_id']."]['fields']=array(".join(',',$profile_categs[$rsrow['pcat_id']]).");\n";
		}
	}
	$towrite.="\n";
	$towrite.='$basic_search_fields=array('.join(',',$basic_search_fields).");\n";
	$modman=new modman();
	$modman->fileop->file_put_contents(_BASEPATH_.'/includes/fields.inc.php',$towrite);
}


function regenerate_langstrings_array() {
	require_once _BASEPATH_.'/includes/classes/modman.class.php';
	global $dbtable_prefix;
	$modman=new modman();
	$query="SELECT a.`module_code`,b.`config_value` as `skin_dir` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`='".MODULE_SKIN."' AND a.`module_code`=b.`fk_module_code` AND b.`config_option`='skin_dir'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$skins[]=$rsrow;
	}
	for ($i=0;isset($skins[$i]);++$i) {
		$towrite="<?php\n";
		$query="SELECT b.`codes` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}locales` b WHERE a.`config_option`='fk_locale_id' AND a.`config_value`=b.`locale_id` AND a.`fk_module_code`='".$skins[$i]['module_code']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$temp=mysql_result($res,0,0);
			$towrite.="setlocale(LC_TIME,array('".str_replace(',',"','",$temp)."'));\n";
		}
		$query="SELECT a.`lk_id`,b.`lang_value` FROM `{$dbtable_prefix}lang_keys` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`lk_id`=b.`fk_lk_id` AND b.`skin`='".$skins[$i]['module_code']."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			$rsrow['lang_value']=sanitize_and_format_gpc($rsrow,'lang_value',TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2EDIT] | FORMAT_ADDSLASH,'');
			$towrite.="\$_lang[".$rsrow['lk_id']."]='".$rsrow['lang_value']."';\n";
		}
		$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$i]['skin_dir'].'/lang/strings.inc.php',$towrite);
	}
}


function set_site_option($option,$module_code,$value) {
	global $dbtable_prefix;
	$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`='$value' WHERE `config_option`='$option' AND `fk_module_code`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


function get_user_by_userid($user_id) {
	$myreturn='';
	global $dbtable_prefix;
	if (!empty($user_id)) {
		$query="SELECT `user` FROM ".USER_ACCOUNTS_TABLE." WHERE `user_id`='$user_id'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


function get_userid_by_user($user) {
	$myreturn=0;
	global $dbtable_prefix;
	if (!empty($user)) {
		$query="SELECT `user_id` FROM ".USER_ACCOUNTS_TABLE." WHERE `user`='$user'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$myreturn=mysql_result($res,0,0);
		}
	}
	return $myreturn;
}


//	$email must have the keys: 'subject','message_body'
//	Both the subject and the message_body are assumed to be NOT sanitized but STRIPSLASH_MQ'ed
function queue_or_send_email($email_addrs,$email,$force_send=false) {
	$myreturn=true;
	if (is_string($email_addrs)) {
		$email_addrs=array($email_addrs);
	}
	$config=get_site_option(array('use_queue','mail_from'),'core');
	$query_len=10000;
	if (!$force_send && !empty($config['use_queue'])) {
		$email['subject']=sanitize_and_format($email['subject'],TYPE_STRING,$GLOBALS['__html2format'][HTML_TEXTFIELD]);
		$email['message_body']=sanitize_and_format($email['message_body'],TYPE_STRING,$GLOBALS['__html2format'][HTML_TEXTAREA]);
		global $dbtable_prefix;
		$base="INSERT INTO `{$dbtable_prefix}queue_email` (`to`,`subject`,`message_body`) VALUES ";
		$query=$base;
		for ($i=0;isset($email_addrs[$i]);++$i) {
			$temp="('".$email_addrs[$i]."','".$email['subject']."','".$email['message_body']."')";
			if (strlen($query)+strlen($temp)<$query_len) {
				$query.=$temp.',';
			} else {
				if ($query!=$base) {
					$query=substr($query,0,-1);
					if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
					$query=$base.$temp.',';
				}
			}
		}
		if ($query!=$base) {
			$query=substr($query,0,-1);
			if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		}
	} else {
		$email['subject']=sanitize_and_format($email['subject'],TYPE_STRING,FORMAT_STRIP_MQ | FORMAT_ONELINE | FORMAT_TRIM);
		$email['message_body']=sanitize_and_format($email['message_body'],TYPE_STRING,FORMAT_STRIP_MQ);
		require_once _BASEPATH_.'/includes/classes/phpmailer.class.php';
		$mail=new PHPMailer();
		$mail->IsHTML(true);
		$mail->From=$config['mail_from'];
		$mail->Sender=$config['mail_from'];
		$mail->FromName=_SITENAME_;
		$mail->LE="\r\n";
		$mail->IsMail();
		for ($i=0;isset($email_addrs[$i]);++$i) {
			$mail->ClearAddresses();
			$mail->AddAddress($email_addrs[$i]);
			$mail->Subject=$email['subject'];
			$mail->Body=$email['message_body'];
			if (!$mail->Send()) {
				$myreturn=false;
				$GLOBALS['topass']['message']['type']=MESSAGE_ERROR;
				$GLOBALS['topass']['message']['text']=$mail->ErrorInfo;
			}
		}
	}
	return $myreturn;
}


function get_default_skin_name() {
	$myreturn='';
	global $dbtable_prefix;
	$query="SELECT a.`module_name` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_code`=b.`fk_module_code` AND a.`module_type`='".MODULE_SKIN."' AND b.`config_option`='is_default' AND b.`config_value`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	if (empty($myreturn)) {
		$myreturn='basic';
	}
	return $myreturn;
}

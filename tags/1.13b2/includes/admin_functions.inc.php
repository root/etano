<?php
/******************************************************************************
Etano
===============================================================================
File:                       admin/includes/admin_functions.inc.php
$Revision$
Software by:                DateMill (http://www.datemill.com)
Copyright by:               DateMill (http://www.datemill.com)
Support at:                 http://www.datemill.com/forum
*******************************************************************************
* See the "docs/licenses/etano.txt" file for license.                         *
******************************************************************************/

if (!defined('_LICENSE_KEY_')) {
	die('Hacking attempt');
}

//if (function_exists('admin_error')) {
//	set_error_handler('admin_error');
//} elseif (function_exists('general_error')) {
	set_error_handler('general_error');
//}

define('IN_ADMIN',1);
require_once 'general_functions.inc.php';
$GLOBALS['_lang']=array();
$def_skin=isset($_SESSION[_LICENSE_KEY_]['admin']['def_skin']) ? $_SESSION[_LICENSE_KEY_]['admin']['def_skin'] : get_default_skin_dir();
require_once _BASEPATH_.'/skins_site/'.$def_skin.'/lang/global.inc.php';
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

// extra links in menus
define('LINKTYPE_MAIN',0);
define('LINKTYPE_PROFILE',1);

$accepted_months=array('month','jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
$accepted_admin_depts=array(DEPT_ADMIN=>'Administrator',DEPT_MODERATOR=>'Moderator');
$accepted_astats=array(ASTAT_SUSPENDED=>'Suspended',ASTAT_UNVERIFIED=>'Unactivated',ASTAT_ACTIVE=>'Active');
$accepted_pstats=array(STAT_PENDING=>'Awaiting approval',STAT_EDIT=>'Requires edit',STAT_APPROVED=>'Approved');
$accepted_yesno=array(0=>'No',1=>'Yes');
$country_prefered_input=array('s'=>'state/city selection','z'=>'zip/postal code');
$flirt_types=array(0=>'Initial flirt',1=>'Reply');
$accepted_module_types=array(MODULE_REGULAR=>'Update',MODULE_PAYMENT=>'Payment',MODULE_FRAUD=>'Fraud Check',MODULE_WIDGET=>'Widget',MODULE_SKIN=>'Skin',MODULE_3RD=>'Integration');

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
	if (isset($_SESSION[_LICENSE_KEY_]['admin']['admin_id'])) {
		if (((int)$levels) & ((int)$_SESSION[_LICENSE_KEY_]['admin']['dept_id'])) {
			$myreturn=true;
		} else {
			$topass['message']='You are not authorized to use this section.';
			redirect2page('admin/cpanel.php',$topass);
		}
	} else {
		$topass['message']['type']=MESSAGE_ERROR;
		$topass['message']['text']='Please login first.';
		$mysession=session_id();
		if (empty($mysession)) {
			session_start();
		}
		$_SESSION[_LICENSE_KEY_]['admin']['timedout']=array('url'=>(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']),'method'=>$_SERVER['REQUEST_METHOD'],'qs'=>($_SERVER['REQUEST_METHOD']=='GET' ? $_GET : $_POST));
		redirect2page('admin/index.php',$topass);
	}
	return $myreturn;
}


/**
 *	Get all field configuration from db and save it in includes/fields.inc.php
 */
function regenerate_fields_array() {
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	global $dbtable_prefix;
	$profile_categs=array();
	$basic_search_fields=array();
	$towrite="<?php\nif (!defined('_LICENSE_KEY_')) {\n\tdie('Hacking attempt');\n}\n";
	$towrite.="require_once _BASEPATH_.'/includes/interfaces/iprofile_field.class.php';\n";
	if ($d = opendir(_BASEPATH_.'/includes/classes/fields')) {
		$includes=array();
		while (false!==($entry=readdir($d))) {
			if (substr($entry,2,6)=='field_') {
				$includes[]=$entry;
			}
		}
		closedir($d);
		unset($d);
		sort($includes);
		for ($i=0;isset($includes[$i]);++$i) {
			$towrite.="require_once _BASEPATH_.'/includes/classes/fields/".$includes[$i]."';\n";
		}
	}
	$towrite.="\n";

	$query="SELECT * FROM `{$dbtable_prefix}profile_fields2` ORDER BY `order_num` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		$custom_config=unserialize($rsrow['custom_config']);
		unset($rsrow['custom_config']);
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
		$id=$rsrow['order_num'];
		$towrite.="\$GLOBALS['_pfields'][$id]=new ".$rsrow['field_type']."(\n\t\t\t\t\t\t\t\tarray(";
		if (!empty($rsrow['for_basic']) && !empty($rsrow['searchable'])) {
			$basic_search_fields[]=$id;
		}
		$profile_categs[$rsrow['fk_pcat_id']][]=$rsrow['order_num'];
		$towrite.="'label'=>&\$GLOBALS['_lang'][".$rsrow['fk_lk_id_label']."],\n";
		if (!empty($rsrow['searchable'])) {
			$towrite.="\t\t\t\t\t\t\t\t\t'searchable'=>true,\n";
			$towrite.="\t\t\t\t\t\t\t\t\t'search_type'=>'".$rsrow['search_type']."',\n";
		}
		$towrite.="\t\t\t\t\t\t\t\t\t'search_label'=>&\$GLOBALS['_lang'][".$rsrow['fk_lk_id_search']."],\n";
		if (!empty($rsrow['at_registration'])) {
			$towrite.="\t\t\t\t\t\t\t\t\t'reg_page'=>".$rsrow['reg_page'].",\n";
		}
		if (!empty($rsrow['required'])) {
			$towrite.="\t\t\t\t\t\t\t\t\t'required'=>true,\n";
		}
		$towrite.="\t\t\t\t\t\t\t\t\t'editable'=>".(!empty($rsrow['editable']) ? 'true' : 'false').",\n";
		$towrite.="\t\t\t\t\t\t\t\t\t'visible'=>".(!empty($rsrow['visible']) ? 'true' : 'false').",\n";
		$towrite.="\t\t\t\t\t\t\t\t\t'dbfield'=>'".$rsrow['dbfield']."',\n";
		$towrite.="\t\t\t\t\t\t\t\t\t'fk_pcat_id'=>".$rsrow['fk_pcat_id'].",\n";
		if (!empty($rsrow['fn_on_change'])) {
			$towrite.="\t\t\t\t\t\t\t\t\t'fn_on_change'=>'".$rsrow['fn_on_change']."',\n";
		}

		$towrite.="\t\t\t\t\t\t\t\t\t'help_text'=>&\$GLOBALS['_lang'][".$rsrow['fk_lk_id_help']."],\n";
		if (is_array($custom_config)) {
			foreach ($custom_config as $k=>$v) {
				if (!is_array($v)) {
					if ($v!=='') {
						$towrite.="\t\t\t\t\t\t\t\t\t'$k'=>$v,\n";
					}
				} else {
					$towrite.="\t\t\t\t\t\t\t\t\t'$k'=>".str_replace("\n",'',var_export($v,true)).",\n";
				}
			}
		}
		$towrite.="\t\t\t\t\t\t\t\t)\n";
		$towrite.="\t\t\t\t\t\t\t);\n";
		$towrite.="\n";
	}
	$towrite.="\n";

	// profile categories now
	$query="SELECT * FROM `{$dbtable_prefix}profile_categories`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($profile_categs[$rsrow['pcat_id']])) {
			$towrite.="\$GLOBALS['_pcats'][".$rsrow['pcat_id']."]['pcat_name']=&\$GLOBALS['_lang'][".$rsrow['fk_lk_id_pcat']."];\n";
			$towrite.="\$GLOBALS['_pcats'][".$rsrow['pcat_id']."]['access_level']=".$rsrow['access_level'].";\n";
			$towrite.="\$GLOBALS['_pcats'][".$rsrow['pcat_id']."]['fields']=array(".join(',',$profile_categs[$rsrow['pcat_id']]).");\n";
		}
	}
	$towrite.="\n";
	$towrite.='$basic_search_fields=array('.join(',',$basic_search_fields).");\n";
	$fileop=new fileop();
	$fileop->file_put_contents(_BASEPATH_.'/includes/fields.inc.php',$towrite);
}


function regenerate_langstrings_array($skin_module_code='') {
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	global $dbtable_prefix;
	$fileop=new fileop();
	if (empty($skin_module_code)) {
		$query="SELECT a.`module_code`,b.`config_value` as `skin_dir` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_SKIN." AND a.`module_code`=b.`fk_module_code` AND b.`config_option`='skin_dir'";
	} else {
		$query="SELECT `fk_module_code` as `module_code`,`config_value` as `skin_dir` FROM `{$dbtable_prefix}site_options3` WHERE `config_option`='skin_dir' AND `fk_module_code`='$skin_module_code'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$skins[]=$rsrow;
	}
	for ($i=0;isset($skins[$i]);++$i) {
		$towrite=array();
		$towrite[''][]='<?php';
		$query="SELECT b.`codes` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}locales` b WHERE a.`config_option`='fk_locale_id' AND a.`config_value`=b.`locale_id` AND a.`fk_module_code`='".$skins[$i]['module_code']."'";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		if (mysql_num_rows($res)) {
			$temp=mysql_result($res,0,0);
			$towrite[''][]="setlocale(LC_TIME,array('".str_replace(',',"','",$temp)."'));";
		}
		$query="SELECT a.`lk_id`,a.`alt_id_text`,b.`lang_value`,a.`lk_use`,a.`save_file` FROM `{$dbtable_prefix}lang_keys` a LEFT JOIN `{$dbtable_prefix}lang_strings` b ON (a.`lk_id`=b.`fk_lk_id` AND b.`skin`='".$skins[$i]['module_code']."')";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		while ($rsrow=mysql_fetch_assoc($res)) {
			if ($rsrow['lk_use']!=LK_FIELD) {
				$rsrow['lang_value']=addcslashes($rsrow['lang_value'],"'\\");
			} else {
				// field related strings cannot contain html code
				$rsrow['lang_value']=sanitize_and_format($rsrow['lang_value'],TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
			}
			if (!empty($rsrow['alt_id_text'])) {
				$rsrow['lk_id']="'".$rsrow['alt_id_text']."'";
			}
			if (!isset($towrite[$rsrow['save_file']])) {
				$towrite[$rsrow['save_file']][]='<?php';
			}
			$towrite[$rsrow['save_file']][]="\$GLOBALS['_lang'][".$rsrow['lk_id']."]='".$rsrow['lang_value']."';";
		}
		foreach ($towrite as $file=>$arr) {
			if (empty($file)) {
				$file='global.inc.php';
			}
			$temp=join("\n",$arr);
			$fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$i]['skin_dir'].'/lang/'.$file,$temp);
		}
	}
}


function regenerate_skin_cache($skin_module_code='',$last_id=0) {
	$timeout=120;
	require _BASEPATH_.'/includes/classes/Cache/Lite.php';
	$cache=new Cache_Lite($GLOBALS['_cache_config']);
	global $dbtable_prefix,$_pfields,$_pcats,$__field2format;
	$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');
	if (empty($skin_module_code)) {
		$query="SELECT b.`config_value` as `skin_dir` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_SKIN." AND a.`module_code`=b.`fk_module_code` AND b.`config_option`='skin_dir'";
	} else {
		$query="SELECT `config_value` as `skin_dir` FROM `{$dbtable_prefix}site_options3` WHERE `config_option`='skin_dir' AND `fk_module_code`='$skin_module_code'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}

	$now=gmdate('YmdHis');
	$select='`fk_user_id`,`status`,`del`,UNIX_TIMESTAMP(`last_changed`) as `last_changed`,UNIX_TIMESTAMP(`date_added`) as `date_added`,`_user`,`_photo`,`rad_longitude`,`rad_latitude`';
	$used_fields=array();
	foreach ($_pfields as $field_id=>&$field) {
		if ($field->config['visible']) {
			$select.=','.$field->query_select();
			$used_fields[]=$field_id;
		}
	}

	// create the cache in every skin
	for ($s=0;isset($skins[$s]);++$s) {
		$GLOBALS['_lang']=array();
		$GLOBALS['_pfields']=array();
		$GLOBALS['_pcats']=array();
		include _BASEPATH_.'/skins_site/'.$skins[$s].'/lang/global.inc.php';
		include _BASEPATH_.'/includes/fields.inc.php';
		$query="SELECT $select FROM `{$dbtable_prefix}user_profiles` WHERE `status`=".STAT_APPROVED;
		if (!empty($last_id)) {
			$query.=" AND `fk_user_id`>$last_id";
		}
		$query.=" ORDER BY `fk_user_id`";
		if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
		$start_time=(int)time();
		while ($profile=mysql_fetch_assoc($res)) {
			for ($i=0;isset($used_fields[$i]);++$i) {
				$field=&$_pfields[$used_fields[$i]];
				$field->set_value($profile,false);
				$profile[$field->config['dbfield']]=$field->display();
				// the label should be set after the call to display(). See field_birthdate::display() for explanation.
				$profile[$field->config['dbfield'].'_label']=$field->config['label'];
			}
			if (empty($profile['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/t2/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/'.$profile['_photo'])) {
				$profile['_photo']='no_photo.gif';
			} else {
				$profile['has_photo']=true;
			}

			$tpl->set_var('profile',$profile);

			// generate the user details for result lists
			$tpl->set_file('temp',$skins[$s].'/static/result_user.html');
			$towrite=$tpl->process('','temp',TPL_OPTIONAL);
			$cache->save($towrite,'skin'.$skins[$s].$profile['fk_user_id'].'result_user');

			// generate the categories to be used on profile.php page
			$categs=array();
			$tpl->set_file('temp',$skins[$s].'/static/profile_categ.html');
			foreach ($_pcats as $pcat_id=>$pcat) {
				$fields=array();
				$j=0;
				for ($k=0;isset($pcat['fields'][$k]);++$k) {
					if (!empty($profile[$_pfields[$pcat['fields'][$k]]->config['dbfield']])) {
						$fields[$j]['label']=$profile[$_pfields[$pcat['fields'][$k]]->config['dbfield'].'_label'];
						$fields[$j]['field']=$profile[$_pfields[$pcat['fields'][$k]]->config['dbfield']];
						$fields[$j]['dbfield']=$_pfields[$pcat['fields'][$k]]->config['dbfield'];
						++$j;
					}
				}
				$categs['pcat_name']=$pcat['pcat_name'];
				$categs['pcat_id']=$pcat_id;
				$tpl->set_loop('fields',$fields);
				$tpl->set_var('categs',$categs);
				$towrite=$tpl->process('','temp',TPL_LOOP);
				$cache->save($towrite,'skin'.$skins[$s].$profile['fk_user_id'].'pcat'.$pcat_id);
				$tpl->drop_loop('fields');
				$tpl->drop_var('categs');
			}
			$tpl->drop_var('profile');
			if (((int)time())-$start_time>$timeout) {
				echo 'To prevent timeouts this script interrupts every few minutes. Press the continue button to resume.<br />';
				echo 'Last user ID processed: ',$profile['fk_user_id'],'<br />';
				echo '<form action="regenerate_skin.php" method="get">';
				echo '<input type="hidden" name="last_id" value="',$profile['fk_user_id'],'" />';
				if (!empty($skin_module_code)) {
					echo '<input type="hidden" name="s" value="',$skin_module_code,'" />';
				}
				echo '<input type="submit" value="Continue" />';
				echo '</form>';
				die;
			}
		}
	}
}


function set_site_option($option,$module_code,$value) {
	global $dbtable_prefix;
	$query="UPDATE `{$dbtable_prefix}site_options3` SET `config_value`='$value' WHERE `config_option`='$option' AND `fk_module_code`='$module_code'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
}


//	$email must have the keys: 'subject','message_body'
//	Both the subject and the message_body are assumed to be NOT sanitized but STRIPSLASH_MQ'ed
function queue_or_send_email($email_addrs,$email,$force_send=false) {
	$myreturn=true;
	if (is_string($email_addrs)) {
		$email_addrs=array($email_addrs);
	}
	$config=get_site_option(array('use_queue','mail_from','mail_crlf'),'core');
	$query_len=10000;
	if (!$force_send && !empty($config['use_queue'])) {
		$email['subject']=sanitize_and_format($email['subject'],TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTFIELD]);
		$email['message_body']=sanitize_and_format($email['message_body'],TYPE_STRING,$GLOBALS['__field2format'][FIELD_TEXTAREA]);
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
		if ($config['mail_crlf']) {
			$mail->LE="\r\n";
		} else {
			$mail->LE="\n";
		}
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
	$query="SELECT a.`module_name` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_code`=b.`fk_module_code` AND a.`module_type`=".MODULE_SKIN." AND b.`config_option`='is_default' AND b.`config_value`=1";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res)) {
		$myreturn=mysql_result($res,0,0);
	}
	if (empty($myreturn)) {
		$myreturn='basic';
	}
	return $myreturn;
}

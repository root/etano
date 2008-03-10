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
$accepted_fieldtype=array(FIELD_SELECT=>'Select box',FIELD_CHECKBOX_LARGE=>'Multi checks',FIELD_TEXTFIELD=>'Textfield',FIELD_TEXTAREA=>'Textarea',FIELD_DATE=>'Date',FIELD_LOCATION=>'Location',FIELD_RANGE=>'Range');
$field_dbtypes=array(FIELD_TEXTFIELD=>"varchar(100) not null default ''",FIELD_SELECT=>'int(5) not null default 0',FIELD_FK_SELECT=>'int(10) not null default 0',FIELD_TEXTAREA=>"text not null default ''",FIELD_CHECKBOX_LARGE=>"text not null default ''",FIELD_FILE=>"varchar(64) not null default ''",FIELD_DATE=>'date not null',FIELD_INT=>'int(5) not null default 0',FIELD_FLOAT=>'double not null default 0');
$accepted_admin_depts=array(DEPT_ADMIN=>'Administrator',DEPT_MODERATOR=>'Moderator');
$accepted_astats=array(ASTAT_SUSPENDED=>'Suspended',ASTAT_UNVERIFIED=>'Unactivated',ASTAT_ACTIVE=>'Active');
$accepted_pstats=array(STAT_PENDING=>'Awaiting approval',STAT_EDIT=>'Requires edit',STAT_APPROVED=>'Approved');
$accepted_yesno=array(0=>'No',1=>'Yes');
$country_prefered_input=array('s'=>'state/city selection','z'=>'zip/postal code');
$inverse_fields=array(2=>'FIELD_TEXTFIELD',3=>'FIELD_SELECT',4=>'FIELD_TEXTAREA',9=>'FIELD_CHECKBOX',10=>'FIELD_CHECKBOX_LARGE',101=>'FIELD_FILE',102=>'FIELD_FK_SELECT',103=>'FIELD_DATE',104=>'FIELD_INT',105=>'FIELD_FLOAT',106=>'HTML_PIC',107=>'FIELD_LOCATION',108=>'FIELD_RANGE');
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


function regenerate_fields_array() {
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	global $dbtable_prefix;
	$query="SELECT * FROM `{$dbtable_prefix}profile_fields` ORDER BY `order_num` ASC";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$towrite="<?php\nif (!defined('_LICENSE_KEY_')) {\n\tdie('Hacking attempt');\n}\n\n";

	$profile_categs=array();
	$basic_search_fields=array();
	while ($rsrow=mysql_fetch_assoc($res)) {
		$rsrow=sanitize_and_format($rsrow,TYPE_STRING,$GLOBALS['__field2format'][TEXT_DB2EDIT]);
		$id=$rsrow['order_num'];
		if (!empty($rsrow['for_basic']) && !empty($rsrow['searchable'])) {
			$basic_search_fields[]=$id;
		}
		$profile_categs[$rsrow['fk_pcat_id']][]=$rsrow['order_num'];
		$towrite.="\$GLOBALS['_pfields'][$id]['label']=\$GLOBALS['_lang'][".$rsrow['fk_lk_id_label']."];\n";
		$towrite.="\$GLOBALS['_pfields'][$id]['field_type']=".$GLOBALS['inverse_fields'][$rsrow['field_type']].";\n";
		if (!empty($rsrow['searchable'])) {
			$towrite.="\$GLOBALS['_pfields'][$id]['searchable']=true;\n";
			$towrite.="\$GLOBALS['_pfields'][$id]['search_type']=".$GLOBALS['inverse_fields'][$rsrow['search_type']].";\n";
		}
		$towrite.="\$GLOBALS['_pfields'][$id]['search_label']=\$GLOBALS['_lang'][".$rsrow['fk_lk_id_search']."];\n";
		if (!empty($rsrow['at_registration'])) {
			$towrite.="\$GLOBALS['_pfields'][$id]['reg_page']=".$rsrow['reg_page'].";\n";
		}
		if (!empty($rsrow['required'])) {
			$towrite.="\$GLOBALS['_pfields'][$id]['required']=true;\n";
		}
		$towrite.="\$GLOBALS['_pfields'][$id]['editable']=".(!empty($rsrow['editable']) ? 'true' : 'false').";\n";
		$towrite.="\$GLOBALS['_pfields'][$id]['visible']=".(!empty($rsrow['visible']) ? 'true' : 'false').";\n";
		$towrite.="\$GLOBALS['_pfields'][$id]['dbfield']='".$rsrow['dbfield']."';\n";
		$towrite.="\$GLOBALS['_pfields'][$id]['fk_pcat_id']=".$rsrow['fk_pcat_id'].";\n";
		if (!empty($rsrow['fn_on_change'])) {
			$towrite.="\$GLOBALS['_pfields'][$id]['fn_on_change']='".$rsrow['fn_on_change']."';\n";
		}

		switch ($rsrow['field_type']) {

			case FIELD_SELECT:
			case FIELD_CHECKBOX_LARGE:
				if (!empty($rsrow['accepted_values']) && $rsrow['accepted_values']!='||') {
					$towrite.="\$GLOBALS['_pfields'][$id]['accepted_values']=array('',\$GLOBALS['_lang'][".str_replace('|',"],\$GLOBALS['_lang'][",substr($rsrow['accepted_values'],1,-1))."]);\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['accepted_values']=array('');\n";
				}
				if (!empty($rsrow['default_value']) && $rsrow['default_value']!='||') {
					$rsrow['default_value']=explode('|',substr($rsrow['default_value'],1,-1));
					// for all fields whose default_values are indexes in accepted_values we increment them with 1 because we
					// add the default value "-" as the first element in every accepted_values array.
					for ($i=0;isset($rsrow['default_value'][$i]);++$i) {
						++$rsrow['default_value'][$i];
					}
					$towrite.="\$GLOBALS['_pfields'][$id]['default_value']=array(".join(',',$rsrow['default_value']).");\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['default_value']=array();\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					// for all fields whose default_searches are indexes in accepted_values we increment them with 1 because we
					// add the default value "-" as the first element in every accepted_values array.
					for ($i=0;isset($rsrow['default_search'][$i]);++$i) {
						++$rsrow['default_search'][$i];
					}
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array(".join(",",$rsrow['default_search']).");\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array();\n";
				}
				break;

			case FIELD_DATE:
				if (!empty($rsrow['accepted_values']) && $rsrow['accepted_values']!='||') {
					$towrite.="\$GLOBALS['_pfields'][$id]['accepted_values']=array('','".str_replace('|',"','",substr($rsrow['accepted_values'],1,-1))."');\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['accepted_values']=array('');\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array(".join(",",$rsrow['default_search']).");\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array();\n";
				}
				break;

			case FIELD_LOCATION:
				if (!empty($rsrow['default_value']) && $rsrow['default_value']!='||') {
					$rsrow['default_value']=explode('|',substr($rsrow['default_value'],1,-1));
					$towrite.="\$GLOBALS['_pfields'][$id]['default_value']=array('".join("','",$rsrow['default_value'])."');\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['default_value']=array();\n";
				}
				if (!empty($rsrow['default_search']) && $rsrow['default_search']!='||') {
					$rsrow['default_search']=explode('|',substr($rsrow['default_search'],1,-1));
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array('".join("','",$rsrow['default_search'])."');\n";
				} else {
					$towrite.="\$GLOBALS['_pfields'][$id]['default_search']=array();\n";
				}
				break;

			case FIELD_TEXTAREA:
			case FIELD_TEXTFIELD:
				break;

		}

		$towrite.="\$GLOBALS['_pfields'][$id]['help_text']=\$GLOBALS['_lang'][".$rsrow['fk_lk_id_help']."];\n";
		$towrite.="\n";
	}
	$towrite.="\n";
	// profile categories now
	$query="SELECT * FROM `{$dbtable_prefix}profile_categories`";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($rsrow=mysql_fetch_assoc($res)) {
		if (isset($profile_categs[$rsrow['pcat_id']])) {
			$towrite.="\$GLOBALS['_pcats'][".$rsrow['pcat_id']."]['pcat_name']=\$GLOBALS['_lang'][".$rsrow['fk_lk_id_pcat']."];\n";
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
	require_once _BASEPATH_.'/includes/classes/fileop.class.php';
	global $dbtable_prefix,$_pfields,$_pcats,$__field2format;
	$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');
	$fileop=new fileop();
	if (empty($skin_module_code)) {
		$query="SELECT b.`config_value` as `skin_dir` FROM `{$dbtable_prefix}modules` a,`{$dbtable_prefix}site_options3` b WHERE a.`module_type`=".MODULE_SKIN." AND a.`module_code`=b.`fk_module_code` AND b.`config_option`='skin_dir'";
	} else {
		$query="SELECT `config_value` as `skin_dir` FROM `{$dbtable_prefix}site_options3` WHERE `config_option`='skin_dir' AND `fk_module_code`='$skin_module_code'";
	}
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}

	$select='`fk_user_id`,`status`,`del`,UNIX_TIMESTAMP(`last_changed`) as `last_changed`,UNIX_TIMESTAMP(`date_added`) as `date_added`,`_user`,`_photo`,`rad_longitude`,`rad_latitude`';
	$now=gmdate('YmdHis');
	foreach ($_pfields as $field_id=>$field) {
		if ($field['field_type']==FIELD_DATE) {
			$select.=",DATE_FORMAT('$now','%Y')-DATE_FORMAT(`".$field['dbfield']."`,'%Y')-(DATE_FORMAT('$now','%m%d')<DATE_FORMAT(`".$field['dbfield']."`,'%m%d')) as `".$field['dbfield']."`";
		} elseif ($field['field_type']==FIELD_LOCATION) {
			$select.=',`'.$field['dbfield'].'_country`,`'.$field['dbfield'].'_state`,`'.$field['dbfield'].'_city`,`'.$field['dbfield'].'_zip`';
		} else {
			$select.=',`'.$field['dbfield'].'`';
		}
	}

	$config=get_site_option(array('bbcode_profile','use_smilies'),'core');
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
			// set all the fields to their real (readable) values
			foreach ($_pfields as $field_id=>$field) {
				if ($field['visible']) {
					$profile[$field['dbfield'].'_label']=$field['label'];
					if ($field['field_type']==FIELD_TEXTFIELD) {
						$profile[$field['dbfield']]=sanitize_and_format(remove_banned_words($profile[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					} elseif ($field['field_type']==FIELD_TEXTAREA) {
						$profile[$field['dbfield']]=sanitize_and_format(remove_banned_words($profile[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
						if ($config['bbcode_profile']) {
							$profile[$field['dbfield']]=bbcode2html($profile[$field['dbfield']]);
						}
						if ($config['use_smilies']) {
							$profile[$field['dbfield']]=text2smilies($profile[$field['dbfield']]);
						}
					} elseif ($field['field_type']==FIELD_SELECT) {
						// if we sanitize here " will be rendered as &quot; which is not what we want
		//				$profile[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$profile[$field['dbfield']]],TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
						$profile[$field['dbfield']]=isset($field['accepted_values'][$profile[$field['dbfield']]]) ? $field['accepted_values'][$profile[$field['dbfield']]] : '?';
					} elseif ($field['field_type']==FIELD_CHECKBOX_LARGE) {
						$profile[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$profile[$field['dbfield']]),TYPE_STRING,$__field2format[TEXT_DB2DISPLAY]);
					} elseif ($field['field_type']==FIELD_INT || $field['field_type']==FIELD_FLOAT) {
			//			$profile[$field['dbfield']]=$profile[$field['dbfield']];
					} elseif ($field['field_type']==FIELD_DATE) {
						$profile[$field['dbfield'].'_label']=$field['search_label'];
						if ($profile[$field['dbfield']]>110) {
							$profile[$field['dbfield']]='?';
						}
					} elseif ($field['field_type']==FIELD_LOCATION) {
						$profile[$field['dbfield']]=db_key2value("`{$dbtable_prefix}loc_countries`",'`country_id`','`country`',$profile[$field['dbfield'].'_country'],'-');
						if (!empty($profile[$field['dbfield'].'_state'])) {
							$profile[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_states`",'`state_id`','`state`',$profile[$field['dbfield'].'_state'],'-');
						}
						if (!empty($profile[$field['dbfield'].'_city'])) {
							$profile[$field['dbfield']].=' / '.db_key2value("`{$dbtable_prefix}loc_cities`",'`city_id`','`city`',$profile[$field['dbfield'].'_city'],'-');
						}
					}
				} else {
					unset($profile[$field['dbfield']]);
				}
			}
			if (empty($profile['_photo']) || !is_file(_PHOTOPATH_.'/t1/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/t2/'.$profile['_photo']) || !is_file(_PHOTOPATH_.'/'.$profile['_photo'])) {
				$profile['_photo']='no_photo.gif';
			} else {
				$profile['has_photo']=true;
			}

			$tpl->set_var('profile',$profile);
			// create the user cache folder if it doesn't exist
			if (!is_dir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'])) {
				$fileop->mkdir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id']);
			}

			// generate the user details for result lists
			$tpl->set_file('temp',$skins[$s].'/static/result_user.html');
			$towrite=$tpl->process('','temp');
			$fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'].'/result_user.html',$towrite);

			// generate the categories to be used on profile.php page
			$categs=array();
			$tpl->set_file('temp',$skins[$s].'/static/profile_categ.html');
			foreach ($_pcats as $pcat_id=>$pcat) {
				$fields=array();
				$j=0;
				for ($i=0;isset($pcat['fields'][$i]);++$i) {
					if (!empty($profile[$_pfields[$pcat['fields'][$i]]['dbfield']])) {
						$fields[$i]['label']=$profile[$_pfields[$pcat['fields'][$i]]['dbfield'].'_label'];
						$fields[$i]['field']=$profile[$_pfields[$pcat['fields'][$i]]['dbfield']];
						$fields[$i]['dbfield']=$_pfields[$pcat['fields'][$i]]['dbfield'];
					}
				}
				$categs['pcat_name']=$pcat['pcat_name'];
				$categs['pcat_id']=$pcat_id;
				$tpl->set_loop('fields',$fields);
				$tpl->set_var('categs',$categs);
				$towrite=$tpl->process('','temp',TPL_LOOP);
				$fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'].'/categ_'.$pcat_id.'.html',$towrite);
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

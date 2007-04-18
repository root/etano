<?
$jobs[]='gen_user_cache';

function gen_user_cache() {
	global $dbtable_prefix;
	global $_pfields;

	$dirname=dirname(__FILE__);
	$temp=array();
	if ($dirname{0}=='/') {				// unixes here
		$temp=explode('/',$dirname);
	} else {							// windows here
		$temp=explode('\\',$dirname);
	}
	$interval=(int)$temp[count($temp)-1];	// that's how often we're executed ;)

	$tpl=new phemplate(_BASEPATH_.'/skins_site/','remove_nonjs');

	$query="SELECT a.`config_value` FROM `{$dbtable_prefix}site_options3` a,`{$dbtable_prefix}modules` b WHERE a.`config_option`='skin_dir' AND a.`fk_module_code`=b.`module_code` AND b.`module_type`='".MODULE_SKIN."'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	$skins=array();
	for ($i=0;$i<mysql_num_rows($res);++$i) {
		$skins[]=mysql_result($res,$i,0);
	}

	$config=get_site_option(array('bbcode_profile'),'core');

	require_once '../../includes/classes/modman.class.php';
	$modman=new modman();

	$select='`fk_user_id`,`status`,`del`,UNIX_TIMESTAMP(`last_changed`) as `last_changed`,UNIX_TIMESTAMP(`date_added`) as `date_added`,`_user`,`_photo`,`longitude`,`latitude`';
	foreach ($_pfields as $field_id=>$field) {
		if ($field['html_type']==HTML_DATE) {
			$select.=",DATE_FORMAT(NOW(),'%Y')-DATE_FORMAT(`".$field['dbfield']."`,'%Y')-(DATE_FORMAT(NOW(),'%m%d')<DATE_FORMAT(`".$field['dbfield']."`,'%m%d')) as `".$field['dbfield']."`";
		} elseif ($field['html_type']==HTML_LOCATION) {
			$select.=',`'.$field['dbfield'].'_country`,`'.$field['dbfield'].'_state`,`'.$field['dbfield'].'_city`,`'.$field['dbfield'].'_zip`';
		} else {
			$select.=',`'.$field['dbfield'].'`';
		}
	}

	$query="SELECT $select FROM `{$dbtable_prefix}user_profiles` WHERE `status`='".STAT_APPROVED."' AND `last_changed`>=DATE_SUB(now(),INTERVAL ".($interval+2)." MINUTE)";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	while ($profile=mysql_fetch_assoc($res)) {
	// set all the fields to their real (readable) values
		foreach ($_pfields as $field_id=>$field) {
			if ($field['visible']) {
				$profile[$field['dbfield'].'_label']=$field['label'];
				if ($field['html_type']==HTML_TEXTFIELD) {
					$profile[$field['dbfield']]=sanitize_and_format($profile[$field['dbfield']],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
				} elseif ($field['html_type']==HTML_TEXTAREA) {
					$profile[$field['dbfield']]=sanitize_and_format($profile[$field['dbfield']],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
					if ($config['bbcode_profile']) {
						$profile[$field['dbfield']]=bbcode2html($profile[$field['dbfield']]);
					}
				} elseif ($field['html_type']==HTML_SELECT) {
					// if we sanitize here " will be rendered as &quot; which is not what we want
	//				$profile[$field['dbfield']]=sanitize_and_format($field['accepted_values'][$profile[$field['dbfield']]],TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
					$profile[$field['dbfield']]=$field['accepted_values'][$profile[$field['dbfield']]];
				} elseif ($field['html_type']==HTML_CHECKBOX_LARGE) {
					$profile[$field['dbfield']]=sanitize_and_format(vector2string_str($field['accepted_values'],$profile[$field['dbfield']]),TYPE_STRING,$GLOBALS['__html2format'][TEXT_DB2DISPLAY]);
				} elseif ($field['html_type']==HTML_INT || $field['html_type']==HTML_FLOAT) {
		//			$profile[$field['dbfield']]=$profile[$field['dbfield']];
				} elseif ($field['html_type']==HTML_DATE) {
					$profile[$field['dbfield'].'_label']=$field['search_label'];
					if ($profile[$field['dbfield']]>110) {
						$profile[$field['dbfield']]='?';
					}
				} elseif ($field['html_type']==HTML_LOCATION) {
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
		// create the cache in every skin
		for ($s=0;isset($skins[$s]);++$s) {
			// create the user cache folder if it doesn't exist
			if (!is_dir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'])) {
				$modman->fileop->mkdir(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id']);
			}

			// generate the user details for result lists
			$tpl->set_file('temp',$skins[$s].'/static/result_user.html');
			$towrite=$tpl->process('','temp');
			$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'].'/result_user.html',$towrite);

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
					}
				}
				$categs['pcat_name']=$pcat['pcat_name'];
				$categs['pcat_id']=$pcat_id;
				$tpl->set_loop('fields',$fields);
				$tpl->set_var('categs',$categs);
				$towrite=$tpl->process('','temp',TPL_LOOP);
				$modman->fileop->file_put_contents(_BASEPATH_.'/skins_site/'.$skins[$s].'/cache/users/'.$profile['fk_user_id']{0}.'/'.$profile['fk_user_id'].'/categ_'.$pcat_id.'.html',$towrite);
				$tpl->drop_loop('fields');
				$tpl->drop_var('categs');
			}
		}
		$tpl->drop_var('user');
	}
	return true;
}
